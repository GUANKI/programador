<?php
class ProgramarController {
    public function index() {
        plantilla("programador/inicio.php");
    }
    
    public function getEvents() {
        $ficha = $_GET['ficha'];
        $db = Database::Conectar();
        $stmt = $db->prepare("SELECT p.*, i.nombre as instructor_nombre FROM programaciones p 
                              JOIN instructores i ON p.instructor_id = i.id 
                              WHERE p.ficha = :ficha");
        $stmt->bindParam(':ficha', $ficha);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Formatear los eventos para que incluyan la propiedad 'title'
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event['instructor_nombre'], // Puedes cambiar esto a cualquier información que quieras mostrar
                'start' => $event['start'],
                'end' => $event['end']
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
    
            $db = Database::Conectar();
    
            // Determinar el rango de horas según la jornada
            switch($jornada) {
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
    
            foreach ($instructores as $instructor) {
                $instructorId = $instructor->id;
    
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
                        echo json_encode(['message' => 'El instructor ya está programado en este horario.']);
                        return;
                    }
                }
    
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
    
}    
?>
