<div class="container">
    <h1 class="my-4">Seleccionar Instructor</h1>
    <form id="assign-form" class="mb-4">
        <input type="hidden" id="ficha" name="ficha" value="<?= $_GET['ficha'] ?>">
        <input type="hidden" id="dates" name="dates" value="<?= $_GET['dates'] ?>">

        <div class="mb-3">
            <label for="instructor_id" class="form-label">ID del Instructor</label>
            <input type="text" class="form-control" id="instructor_id" name="instructor_id" required>
        </div>
        <div class="mb-3">
            <label for="resultado_aprendizaje" class="form-label">Resultado de Aprendizaje</label>
            <input type="text" class="form-control" id="resultado_aprendizaje" name="resultado_aprendizaje" required>
        </div>
        <button type="button" class="btn btn-primary" id="assign-button">Programar Instructor</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#assign-button').click(function() {
            var ficha = $('#ficha').val();
            var instructorId = $('#instructor_id').val();
            // var resultadoAprendizaje = $('#resultado_aprendizaje').val();
            var dates = $('#dates').val().split(",");
            

            dates.forEach(function(date) {
                var start = date + "T00:00:00";
                var end = date + "T23:59:59";
                $.ajax({
                    url: '?c=programar&a=programarInstructor',
                    method: 'POST',
                    data: {
                        ficha: ficha,
                        instructor_id: instructorId,
                        resultado_aprendizaje: resultadoAprendizaje,
                        start: start,
                        end: end
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.href = '?c=programar&a=index';
                    }
                });
            });
        });
    });
</script>
