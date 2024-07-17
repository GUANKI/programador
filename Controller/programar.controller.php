<?php
class ProgramarController
{
    public function index()
    {
        plantilla("programador/inicio.php");
    }
    public function indexInstructor()
    {
        plantilla("programador/instructor.php");
    }
    //POR FICHA
    public function getEvents()
    {
        $ficha = $_GET['ficha'];
        $db = Database::Conectar();
        $stmt = $db->prepare("SELECT p.*, i.nombre as instructor_nombre,  i.apellido as instructor_apellido FROM programaciones p 
                              JOIN instructores i ON p.instructor_id = i.id 
                              WHERE p.ficha = :ficha");
        $stmt->bindParam(':ficha', $ficha);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formatear los eventos para que incluyan la propiedad 'title' y las extendedProps
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event['instructor_nombre'] . " - " . $event["resultado_aprendizaje"],
                'start' => $event['start'],
                'end' => $event['end'],
                'extendedProps' => [
                    'ficha' => $event['ficha'],
                    'resultado_aprendizaje' => $event['resultado_aprendizaje'],
                    'id' => $event['id'],
                    'instructor_nombre' => $event['instructor_nombre'] . " " . $event["instructor_apellido"]
                ]
            ];
        }

        echo json_encode($formattedEvents);
    }

    public function programarInstructor()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ficha = $data['ficha'];
            $instructores = json_decode($data['instructores']);
            $resultadoAprendizaje = $data['resultado_aprendizaje'];
            $selectedDates = $data['selectedDates'];
            $jornada = $data['jornada'];
            $horaInicio = $data['horaInicio'];
            $horaFin = $data['horaFin'];
            $force = isset($data['force']) ? $data['force'] : false;

            $db = Database::Conectar();

            // Determinar el rango de horas según la jornada
            switch ($jornada) {
                case 'mañana':
                    $startTime = "06:00:00";
                    $endTime = "11:59:59";
                    $hoursPerDay = 6;
                    break;
                case 'tarde':
                    $startTime = "12:00:00";
                    $endTime = "17:59:59";
                    $hoursPerDay = 6;
                    break;
                case 'noche':
                    $startTime = "18:00:00";
                    $endTime = "23:00:00";
                    $hoursPerDay = 5;
                    break;
                case 'personalizada':
                    if ($horaInicio && $horaFin) {
                        $startTime = $horaInicio;
                        $endTime = $horaFin;
                        $startTimeObj = new DateTime($horaInicio);
                        $endTimeObj = new DateTime($horaFin);
                        $hoursPerDay = $endTimeObj->diff($startTimeObj)->h;
                    } else {
                        echo json_encode(['message' => 'Debe seleccionar una hora de inicio y fin para la jornada personalizada.']);
                        return;
                    }
                    break;
                default:
                    echo json_encode(['message' => 'Jornada no válida.']);
                    return;
            }

            foreach ($instructores as $index => $instructor) {
                $instructorId = $instructor->id;
                $tipoInstructorQuery = $db->prepare("SELECT tipo_id FROM instructores WHERE id = :instructor_id");
                $tipoInstructorQuery->bindParam(':instructor_id', $instructorId);
                $tipoInstructorQuery->execute();
                $tipoInstructor = $tipoInstructorQuery->fetch(PDO::FETCH_ASSOC)['tipo_id'];

                // Obtener el límite de horas para el tipo de instructor desde la tabla tipos_instructores
                $limiteHorasQuery = $db->prepare("SELECT horas_maximas FROM tipos_instructores WHERE id = :tipo_instructor");
                $limiteHorasQuery->bindParam(':tipo_instructor', $tipoInstructor);
                $limiteHorasQuery->execute();
                $limiteHoras = $limiteHorasQuery->fetch(PDO::FETCH_ASSOC)['horas_maximas'];

                // Verificar horas acumuladas del instructor
                $currentMonth = date('n');
                $currentYear = date('Y');
                $hoursQuery = $db->prepare("SELECT SUM(hours) as total_hours FROM horas_acumuladas WHERE instructor_id = :instructor_id AND month = :month AND year = :year");
                $hoursQuery->bindParam(':instructor_id', $instructorId);
                $hoursQuery->bindParam(':month', $currentMonth);
                $hoursQuery->bindParam(':year', $currentYear);
                $hoursQuery->execute();
                $currentHours = $hoursQuery->fetch(PDO::FETCH_ASSOC)['total_hours'];

                // Verificar si supera el límite de horas
                if ($currentHours + $hoursPerDay > $limiteHoras && !$force) {
                    $mensaje = "¡Advertencia! El instructor ha alcanzado el límite de horas ($limiteHoras horas). Horas acumuladas hasta ahora: $currentHours horas.";
                    echo json_encode(['confirm' => true, 'message' => $mensaje]);
                    return;
                }

                // Verificar disponibilidad del instructor
                foreach ($selectedDates as $date) {
                    $start = $date . "T" . $startTime;
                    $end = $date . "T" . $endTime;

                    $stmt = $db->prepare("SELECT * FROM programaciones WHERE instructor_id = :instructor_id AND (
                    (start <= :start AND end >= :start) OR 
                    (start <= :end AND end >= :end) OR 
                    (start >= :start AND end <= :end)
                    )");
                    $stmt->bindParam(':instructor_id', $instructorId);
                    $stmt->bindParam(':start', $start);
                    $stmt->bindParam(':end', $end);
                    $stmt->execute();
                    $conflicts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($conflicts) > 0) {
                        $programaConflicto = $conflicts[0]['ficha'];
                        echo json_encode(['type' => 'error', 'message' => 'El instructor ya está programado en este horario con la ficha ' . $programaConflicto . '.']);
                        return;
                    }

                    $stmt = $db->prepare("SELECT * FROM programaciones WHERE instructor_id = :instructor_id AND DATE(start) = :date");
                    $stmt->bindParam(':instructor_id', $instructorId);
                    $stmt->bindParam(':date', $date);
                    $stmt->execute();
                    $conflicts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $fichasProgramadas = array_column($conflicts, 'ficha');
                    $diasProgramados = array_column($conflicts, 'start');

                    if (!$force) {
                        if ($tipoInstructor == 2 && count($conflicts) > 0) {
                            $mensaje = 'El instructor ya está programado el día ' . implode(', ', array_map(function ($d) {
                                return date('d \d\e F', strtotime($d));
                            }, $diasProgramados)) . ' con la(s) ficha(s) ' . implode(', ', $fichasProgramadas) . '. ¿Desea programar de todas formas?';
                            echo json_encode(['confirm' => true, 'message' => $mensaje]);
                            return;
                        }
                        if ($tipoInstructor == 1 && count($conflicts) > 1) {
                            $mensaje = 'El instructor ya está programado dos veces el día ' . implode(', ', array_map(function ($d) {
                                return date('d \d\e F', strtotime($d));
                            }, $diasProgramados)) . ' con la(s) ficha(s) ' . implode(', ', $fichasProgramadas) . '. ¿Desea programar de todas formas?';
                            echo json_encode(['confirm' => true, 'message' => $mensaje]);
                            return;
                        }
                    }
                }

                // Insertar la nueva programación y actualizar las horas acumuladas
                foreach ($selectedDates as $date) {
                    $start = $date . "T" . $startTime;
                    $end = $date . "T" . $endTime;

                    $stmt = $db->prepare("INSERT INTO programaciones (ficha, instructor_id, start, end, resultado_aprendizaje) VALUES (:ficha, :instructor_id, :start, :end, :resultado_aprendizaje)");
                    $stmt->bindParam(':ficha', $ficha);
                    $stmt->bindParam(':instructor_id', $instructorId);
                    $stmt->bindParam(':start', $start);
                    $stmt->bindParam(':end', $end);
                    $stmt->bindParam(':resultado_aprendizaje', $resultadoAprendizaje);
                    $stmt->execute();

                    // Calcular las horas acumuladas
                    $year = date('Y', strtotime($date));
                    $month = date('n', strtotime($date));

                    $hoursQuery = $db->prepare("SELECT * FROM horas_acumuladas WHERE instructor_id = :instructor_id AND year = :year AND month = :month");
                    $hoursQuery->bindParam(':instructor_id', $instructorId);
                    $hoursQuery->bindParam(':year', $year);
                    $hoursQuery->bindParam(':month', $month);
                    $hoursQuery->execute();
                    $hoursRecord = $hoursQuery->fetch(PDO::FETCH_ASSOC);

                    if ($hoursRecord) {
                        $newHours = $hoursRecord['hours'] + $hoursPerDay;
                        $updateHoursQuery = $db->prepare("UPDATE horas_acumuladas SET hours = :hours WHERE id = :id");
                        $updateHoursQuery->bindParam(':hours', $newHours);
                        $updateHoursQuery->bindParam(':id', $hoursRecord['id']);
                        $updateHoursQuery->execute();
                    } else {
                        $insertHoursQuery = $db->prepare("INSERT INTO horas_acumuladas (instructor_id, year, month, hours) VALUES (:instructor_id, :year, :month, :hours)");
                        $insertHoursQuery->bindParam(':instructor_id', $instructorId);
                        $insertHoursQuery->bindParam(':year', $year);
                        $insertHoursQuery->bindParam(':month', $month);
                        $insertHoursQuery->bindParam(':hours', $hoursPerDay);
                        $insertHoursQuery->execute();
                    }
                }
            }

            echo json_encode(['type' => 'success', 'message' => 'Instructor programado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

















    //POR INSTRUCTOR

    public function getInstructorEvents()
    {
        $instructor = $_GET['instructor'];
        $db = Database::Conectar();
        $stmt = $db->prepare("SELECT p.*, i.nombre as instructor_nombre, i.apellido as instructor_apellido FROM programaciones p 
                              JOIN instructores i ON p.instructor_id = i.id 
                              WHERE CONCAT(i.nombre, ' ', i.apellido) LIKE :instructor");
        $instructor = "%" . $instructor . "%";
        $stmt->bindParam(':instructor', $instructor);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event['ficha'],
                'start' => $event['start'],
                'end' => $event['end'],
                'extendedProps' => [
                    'ficha' => $event['ficha'],
                    'resultadoAprendizaje' => $event['resultado_aprendizaje'],
                    'instructor' => $event['instructor_nombre'] . ' ' . $event['instructor_apellido']
                ]
            ];
        }

        echo json_encode($formattedEvents);
    }

    public function GetEvents2()
    {
        $instructorId = $_GET['instructorId'];
        $db = Database::Conectar();
        $stmt = $db->prepare("SELECT p.*, i.nombre as instructor_nombre, i.apellido as instructor_apellido FROM programaciones p 
                          JOIN instructores i ON p.instructor_id = i.id 
                          WHERE p.instructor_id = :instructorId");
        $stmt->bindParam(':instructorId', $instructorId);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event['ficha'] . " - " . $event['resultado_aprendizaje'],
                'start' => $event['start'],
                'end' => $event['end'],
                'ficha' => $event['ficha'],
                'resultadoAprendizaje' => $event['resultado_aprendizaje'],
                'horaInicio' => date('H:i', strtotime($event['start'])),
                'horaFin' => date('H:i', strtotime($event['end'])),
                'instructorNombre' => $event['instructor_nombre'] . ' ' . $event['instructor_apellido']
            ];
        }

        echo json_encode($formattedEvents);
    }

    public function eliminarEvento()
    {
        $id = $_POST['id'];
        $db = Database::Conectar();

        // Obtener la información del evento antes de eliminarlo
        $sql = "SELECT instructor_id, start, end FROM programaciones WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            // Calcular las horas del evento con mayor precisión
            $start = new DateTime($event['start']);
            $end = new DateTime($event['end']);
            $interval = $start->diff($end);
            $hours = $interval->h + ($interval->i / 60) + ($interval->s / 3600); // Calcula horas totales considerando minutos y segundos

            $year = $start->format('Y');
            $month = $start->format('n');
            $instructorId = $event['instructor_id'];

            // Actualizar las horas acumuladas
            $sql = "SELECT * FROM horas_acumuladas WHERE instructor_id = :instructor_id AND year = :year AND month = :month";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':instructor_id', $instructorId);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':month', $month);
            $stmt->execute();
            $hoursRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($hoursRecord) {
                $newHours = $hoursRecord['hours'] - $hours;
                if ($newHours <= 0) {
                    // Si las horas restantes son 0 o menos, eliminar el registro
                    $sql = "DELETE FROM horas_acumuladas WHERE id = :id";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':id', $hoursRecord['id']);
                } else {
                    // Si las horas restantes son mayores a 0, actualizar el registro
                    $sql = "UPDATE horas_acumuladas SET hours = :hours WHERE id = :id";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':hours', $newHours);
                    $stmt->bindParam(':id', $hoursRecord['id']);
                }
                $stmt->execute();
            }

            // Eliminar el evento
            $sql = "DELETE FROM programaciones WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Evento eliminado exitosamente.']);
            } else {
                echo json_encode(['message' => 'Error al eliminar el evento.']);
            }
        } else {
            echo json_encode(['message' => 'El evento no existe.']);
        }
    }


    // public function modificarEvento() {
    //     $data = json_decode(file_get_contents('php://input'), true);
    //     $id = $data['id'];
    //     $resultado = $data['resultado_aprendizaje'];
    //     $instructor = $data['instructor_nombre'];

    //     $db = Database::Conectar();
    //     $sql = "UPDATE programaciones SET resultado_aprendizaje = :resultado, instructor_nombre = :instructor WHERE id = :id";
    //     $stmt = $db->prepare($sql);
    //     $stmt->bindParam(':resultado', $resultado, PDO::PARAM_STR);
    //     $stmt->bindParam(':instructor', $instructor, PDO::PARAM_STR);
    //     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //     if ($stmt->execute()) {
    //         echo json_encode(['message' => 'Evento modificado exitosamente.']);
    //     } else {
    //         echo json_encode(['message' => 'Error al modificar el evento.']);
    //     }
    // }
}
