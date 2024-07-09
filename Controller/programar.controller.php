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
        $stmt = $db->prepare("SELECT p.*, i.nombre as instructor_nombre FROM programaciones p 
                              JOIN instructores i ON p.instructor_id = i.id 
                              WHERE p.ficha = :ficha");
        $stmt->bindParam(':ficha', $ficha);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formatear los eventos para que incluyan la propiedad 'title' y las extendedProps
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event['instructor_nombre'],
                'start' => $event['start'],
                'end' => $event['end'],
                'extendedProps' => [
                    'ficha' => $event['ficha'],
                    'resultado_aprendizaje' => $event['resultado_aprendizaje'],
                    'id' => $event['id'],
                    'instructor_nombre' => $event['instructor_nombre']
                ]
            ];
        }

        echo json_encode($formattedEvents);
    }

    public function programarInstructor() {
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
                    break;
                case 'tarde':
                    $startTime = "12:00:00";
                    $endTime = "17:59:59";
                    break;
                case 'noche':
                    $startTime = "18:00:00";
                    $endTime = "23:00:00";
                    break;
                case 'personalizada':
                    if ($horaInicio && $horaFin) {
                        $startTime = $horaInicio;
                        $endTime = $horaFin;
                    } else {
                        echo json_encode(['message' => 'Debe seleccionar una hora de inicio y fin para la jornada personalizada.']);
                        return;
                    }
                    break;
                default:
                    echo json_encode(['message' => 'Jornada no válida.']);
                    return;
            }
    
            $conflictMessages = [];
            $conflictDetails = [];
    
            foreach ($instructores as $instructor) {
                $instructorId = $instructor->id;
                $tipoInstructorQuery = $db->prepare("SELECT tipo_id FROM instructores WHERE id = :instructor_id");
                $tipoInstructorQuery->bindParam(':instructor_id', $instructorId);
                $tipoInstructorQuery->execute();
                $tipoInstructor = $tipoInstructorQuery->fetch(PDO::FETCH_ASSOC)['tipo_id'];
    
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
                        $fichasProgramadas = array_column($conflicts, 'ficha');
                        $diasProgramados = array_column($conflicts, 'start');
    
                        foreach ($diasProgramados as $index => $dia) {
                            $conflictDetails[] = 'El instructor ya está programado el ' . strftime('%d de %B', strtotime($dia)) . ' con el programa de ficha ' . $fichasProgramadas[$index];
                        }
    
                        if (!$force) {
                            if ($tipoInstructor == 2 && count($conflicts) > 0) {
                                $mensaje = 'El instructor ya está programado el día ' . implode(', ', array_map(function ($d) {
                                    return strftime('%d de %B', strtotime($d));
                                }, $diasProgramados)) . ' con el programa de ficha(s) ' . implode(', ', $fichasProgramadas) . '. ¿Desea programar de todas formas?';
                                echo json_encode(['confirm' => true, 'message' => $mensaje]);
                                return;
                            }
                            if ($tipoInstructor == 1 && count($conflicts) > 1) {
                                $mensaje = 'El instructor ya está programado dos veces el día ' . implode(', ', array_map(function ($d) {
                                    return strftime('%d de %B', strtotime($d));
                                }, $diasProgramados)) . ' con el programa de ficha(s) ' . implode(', ', $fichasProgramadas) . '. ¿Desea programar de todas formas?';
                                echo json_encode(['confirm' => true, 'message' => $mensaje]);
                                return;
                            }
                        }
                    }
                }
            }
    
            if (!empty($conflictMessages)) {
                $mensaje = implode('<br>', $conflictMessages) . ' ¿Desea programar de todas formas?';
                echo json_encode(['confirm' => true, 'message' => $mensaje]);
                return;
            }
    
            foreach ($instructores as $instructor) {
                $instructorId = $instructor->id;
    
                // Insertar la nueva programación
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
                }
            }
    
            echo json_encode(['message' => 'Instructor programado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['message' => 'Ocurrió un error: ' . $e->getMessage()]);
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
        $stmt = $db->prepare("SELECT p.*, i.nombre as instructor_nombre FROM programaciones p 
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
                'horaFin' => date('H:i', strtotime($event['end']))
            ];
        }

        echo json_encode($formattedEvents);
    }

    public function eliminarEvento()
    {
        $id = $_POST['id'];
        $db = Database::Conectar();
        $sql = "DELETE FROM programaciones WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Evento eliminado exitosamente.']);
        } else {
            echo json_encode(['message' => 'Error al eliminar el evento.']);
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
