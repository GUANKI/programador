<form action="?c=formacion&a=guardar" method="post">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Programa de Formación</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="nivel" class="form-label">Nivel de Formación</label>
        <select class="form-select" id="nivel" name="nivel" required>
            <option value="Tecnico">Técnico</option>
            <option value="Tecnologo">Tecnólogo</option>
            <option value="Operario">Operario</option>
            <option value="Complementario">Complementario</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="ambiente" class="form-label">Ambiente</label>
        <input type="text" class="form-control" id="ambiente" name="ambiente" required>
    </div>
    <div class="mb-3">
        <label for="ficha" class="form-label">Número de Ficha</label>
        <input type="text" class="form-control" id="ficha" name="ficha" required>
    </div>
    <div class="mb-3" id="horario-container">
        <label for="horario" class="form-label">Horario</label>
        <select class="form-select" id="horario" name="horario">
            <option value="">Seleccione un horario (opcional)</option>
            <option value="Mañana">Mañana (6:00 a 11:59)</option>
            <option value="Tarde">Tarde (12:00 a 17:59)</option>
            <option value="Noche">Noche (18:00 a 22:59)</option>
        </select>
    </div>
    <div class="mb-3" id="custom-horario-container" style="display:none;">
        <label for="hora_inicio" class="form-label">Hora de Inicio</label>
        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio">
        <label for="hora_fin" class="form-label">Hora de Fin</label>
        <input type="time" class="form-control" id="hora_fin" name="hora_fin">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nivelSelect = document.getElementById('nivel');
    const horarioContainer = document.getElementById('horario-container');
    const customHorarioContainer = document.getElementById('custom-horario-container');
    const horarioSelect = document.getElementById('horario');
    const horaInicioInput = document.getElementById('hora_inicio');
    const horaFinInput = document.getElementById('hora_fin');

    nivelSelect.addEventListener('change', function() {
        if (this.value === 'Complementario') {
            horarioContainer.style.display = 'none';
            customHorarioContainer.style.display = 'block';
            horarioSelect.removeAttribute('required');
            horaInicioInput.setAttribute('required', 'required');
            horaFinInput.setAttribute('required', 'required');
        } else {
            horarioContainer.style.display = 'block';
            customHorarioContainer.style.display = 'none';
            horarioSelect.setAttribute('required', 'required');
            horaInicioInput.removeAttribute('required');
            horaFinInput.removeAttribute('required');
        }
    });
});
</script>
