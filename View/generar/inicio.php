<?php
// Función para obtener el nombre del mes en español
function getSpanishMonthName($monthNumber) {
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
?>
<div class="container mt-5">
    <h1>Reporte Mensual de Horas de Instructores</h1>

    <!-- Formulario de Filtro -->
    <form action="?c=generar&a=inicio" method="post" class="mt-4">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="mes">Seleccione el Mes</label>
                <select name="mes" id="mes" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="year">Seleccione el Año</label>
                <select name="year" id="year" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="tipo_id">Seleccione el Tipo de Instructor</label>
                <select name="tipo_id" id="tipo_id" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($tipos_instructores as $tipo): ?>
                        <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['descripcion']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>&nbsp;</label><br>
                <button type="submit" class="btn btn-primary">Filtrar Datos</button>
                <a href="?c=generar&a=excel" class="btn btn-success">Descargar Excel</a>
            </div>
        </div>
    </form>

    <!-- Tabla de Resultados -->
    <?php if (!empty($reporte)): ?>
        <div class="mt-5">
            <h3>Resultados del Reporte</h3>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Instructor</th>
                        <th>Tipo de Instructor</th>
                        <th>Horas Acumuladas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reporte as $item): ?>
                        <tr>
                            <td><?php echo getSpanishMonthName($item['month']); ?></td>
                            <td><?php echo $item['instructor_nombre']; ?></td>
                            <td><?php echo $item['tipo_instructor']; ?></td>
                            <td><?php echo $item['horas_acumuladas']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="mt-5">
            <p>No se encontraron resultados para el filtro seleccionado.</p>
        </div>
    <?php endif; ?>

</div>