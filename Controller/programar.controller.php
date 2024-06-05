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
        $ficha = $_POST['ficha'];
        $instructorId = $_POST['instructor_id'];
        $selectedDates = $_POST['selectedDates']; // Array of dates

        $db = Database::Conectar();

        foreach ($selectedDates as $date) {
            $start = $date . "T00:00:00";
            $end = $date . "T23:59:59";

            // Check if the instructor is available for the given time
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

            // Insert the new schedule
            $stmt = $db->prepare("INSERT INTO programaciones (ficha, instructor_id, start, end) VALUES (:ficha, :instructor_id, :start, :end)");
            $stmt->bindParam(':ficha', $ficha);
            $stmt->bindParam(':instructor_id', $instructorId);
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->execute();

            // Update horas_acumuladas table
            $date = new DateTime($start);
            $month = $date->format('m');
            $year = $date->format('Y');

            $stmt = $db->prepare("INSERT INTO horas_acumuladas (instructor_id, mes, anio, horas_acumuladas)
                                  VALUES (:instructor_id, :mes, :anio, :horas)
                                  ON DUPLICATE KEY UPDATE horas_acumuladas = horas_acumuladas + :horas");
            $stmt->bindParam(':instructor_id', $instructorId);
            $stmt->bindParam(':mes', $month);
            $stmt->bindParam(':anio', $year);
            $stmt->bindValue(':horas', $this->calcularHoras($ficha), PDO::PARAM_INT);
            $stmt->execute();
        }

        echo json_encode(['message' => 'Instructor programado exitosamente.']);
    }

    private function calcularHoras($ficha) {
        // Aquí deberías implementar la lógica para calcular las horas basadas en la jornada de la formación
        // Por ejemplo:
        // - Mañana: 6 horas
        // - Tarde: 6 horas
        // - Noche: 5 horas
        // Retornando un valor de ejemplo por ahora
        return 6; // Este valor debe ser calculado dinámicamente
    }
}

?>