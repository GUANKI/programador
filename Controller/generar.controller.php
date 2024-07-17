<?php
require 'vendor/autoload.php'; // Ruta al autoload generado por Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Generarcontroller {
    public function inicio(){
        $db = Database::Conectar();
        $sql = "SELECT id, descripcion FROM tipos_instructores";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $tipos_instructores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reporte = [];
        if (!empty($_POST['mes']) && !empty($_POST['year']) && !empty($_POST['tipo_id'])) {
            $mes = $_POST['mes'];
            $year = $_POST['year'];
            $tipo_id = $_POST['tipo_id'];

            // Obtener datos filtrados de la base de datos
            $sql = "SELECT CONCAT(i.nombre, ' ', i.apellido) as instructor_nombre, ti.descripcion as tipo_instructor, ha.hours as horas_acumuladas, ha.month, ha.year
                    FROM horas_acumuladas ha
                    JOIN instructores i ON ha.instructor_id = i.id
                    JOIN tipos_instructores ti ON i.tipo_id = ti.id
                    WHERE ha.month = :mes AND ha.year = :year AND i.tipo_id = :tipo_id
                    ORDER BY ha.year ASC, ha.month ASC";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_id', $tipo_id, PDO::PARAM_INT);
            $stmt->execute();
            $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        plantilla("generar/inicio.php", ['tipos_instructores' => $tipos_instructores, 'reporte' => $reporte]);
    }
    public function excel() {
        // Crear una instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Establecer propiedades globales del documento
        $spreadsheet->getProperties()
                    ->setCreator("Sena")
                    ->setLastModifiedBy("Sena")
                    ->setTitle("Reporte Mensual de Horas de Instructores")
                    ->setSubject("Reporte Mensual de Horas de Instructores")
                    ->setDescription("Reporte mensual de horas de instructores");

        // Obtener datos de la base de datos
        $db = Database::Conectar(); // Asegúrate de tener la conexión a la base de datos

        $sql = "SELECT CONCAT(i.nombre, ' ', i.apellido) as instructor_nombre, ti.descripcion as tipo_instructor, ha.hours as horas_acumuladas, ha.month, ha.year
                FROM horas_acumuladas ha
                JOIN instructores i ON ha.instructor_id = i.id
                JOIN tipos_instructores ti ON i.tipo_id = ti.id
                ORDER BY ha.year ASC, ha.month ASC"; // Ordenar por año y mes

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar resultados por año y mes
        $monthsData = [];
        foreach ($results as $result) {
            $year = $result['year'];
            $month = $result['month'];
            if (!isset($monthsData[$year])) {
                $monthsData[$year] = [];
            }
            if (!isset($monthsData[$year][$month])) {
                $monthsData[$year][$month] = [];
            }
            $monthsData[$year][$month][] = $result;
        }

        // Crear hoja de cálculo por cada mes y año
        foreach ($monthsData as $year => $yearData) {
            foreach ($yearData as $month => $data) {
                // Crear una nueva hoja para el mes y año
                $monthName = $this->getSpanishMonthName($month); // Obtener nombre del mes en español
                $sheetName = "{$monthName} {$year}";
                $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $sheetName);

                // Establecer hoja activa y agregarla al libro
                $spreadsheet->addSheet($sheet);
                $spreadsheet->setActiveSheetIndexByName($sheetName);

                // Escribir encabezados en la hoja
                $sheet->setCellValue('A1', 'Mes')
                      ->setCellValue('B1', 'Instructor')
                      ->setCellValue('C1', 'Tipo de Instructor')
                      ->setCellValue('D1', 'Horas Acumuladas');

                // Llenar los datos en la hoja de cálculo
                $row = 2;
                foreach ($data as $result) {
                    $sheet->setCellValue('A' . $row, $monthName)
                          ->setCellValue('B' . $row, $result['instructor_nombre'])
                          ->setCellValue('C' . $row, $result['tipo_instructor'])
                          ->setCellValue('D' . $row, $result['horas_acumuladas']);
                    $row++;
                }

                // Establecer anchos de columna
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(20);
            }
        }

        // Eliminar la hoja de cálculo por defecto (Worksheet)
        $spreadsheet->removeSheetByIndex(0);

        // Crear una respuesta de tipo Xlsx
        $filename = "reporte_mensual_instructores_" . date('Y') . ".xlsx";
        $writer = new Xlsx($spreadsheet);

        // Configurar las cabeceras para la descarga del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Salida directa del archivo Excel al navegador
        $writer->save('php://output');
        exit;
    }

    private function getSpanishMonthName($monthNumber) {
        $monthNames = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        return $monthNames[$monthNumber] ?? '';
    }
}
?>
