<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Programar Instructores - SENA</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
</head>
<body>
    <h1>Programar Instructores</h1>
    <form action="/instructor/programar" method="POST">
        <label for="instructor">Seleccionar Instructor:</label>
        <select name="instructor" id="instructor">
            <?php foreach ($data['instructores'] as $instructor): ?>
                <option value="<?= $instructor['id'] ?>"><?= $instructor['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="fecha">Seleccionar Fecha:</label>
        <input type="date" name="fecha" id="fecha">
        <label for="hora_inicio">Hora de Inicio:</label>
        <input type="time" name="hora_inicio" id="hora_inicio">
        <label for="hora_fin">Hora de Fin:</label>
        <input type="time" name="hora_fin" id="hora_fin">
        <button type="submit">Programar</button>
    </form>
</body>
</html>