<style>
    .fc-highlight {
        background-color: #ffc107 !important;
    }
    #result p {
        cursor: pointer;
        padding: 5px;
        border-bottom: 1px solid #ccc;
    }
    #result p:hover {
        background-color: #f0f0f0;
    }
</style>

<div class="container">
    <h1 class="my-4">Programar Instructores</h1>
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" id="ficha" name="ficha" placeholder="Ingrese el número de ficha">
            <button type="button" class="btn btn-primary" id="search-button">Buscar</button>
        </div>
    </form>
    <div id='calendar'></div>
    <div class="my-4 text-center">
        <button type="button" class="btn btn-success btn-lg" id="btn-programar" data-bs-toggle="modal" data-bs-target="#programarModal">
            PROGRAMAR
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {
        var selectedDates = [];

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            selectable: true,
            select: function(info) {
                selectedDates.push(info.start);
                var formattedDates = selectedDates.map(date => date.toLocaleDateString('es-ES', {
                    day: 'numeric',
                    month: 'long',
                    color: '#ffc107'
                }));
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                var ficha = document.getElementById('ficha').value;
                if (ficha) {
                    fetch(`?c=programar&a=getEvents&ficha=${ficha}`)
                        .then(response => response.json())
                        .then(events => successCallback(events))
                        .catch(error => failureCallback(error));
                }
            }
        });
        calendar.render();

        $('#search-button').click(function() {
            var ficha = $('#ficha').val();
            if (ficha) {
                calendar.refetchEvents();
            } else {
                alert("Ingrese un número de ficha.");
            }
        });

        $("#btn-programar").click(function() {
            var formattedDates = selectedDates.map(date => date.toLocaleDateString('es-ES', {
                day: 'numeric',
                month: 'long'
            }));
            $("#dias_a_programar").html(formattedDates.join("<br>"));
            $("#programarModal").modal("show");
        });

        $('#programar-form').submit(function(event) {
            event.preventDefault();
            const { value } = document.querySelector("#select-value");
            var resultadoAprendizaje = $('#resultadoAprendizaje').val();
            var ficha = $('#ficha').val();

            var dates = selectedDates.map(date => date.toISOString().split('T')[0]);

            var data = {
                instructores: value,
                resultado_aprendizaje: resultadoAprendizaje,
                ficha: ficha,
                selectedDates: dates
            };

            $.ajax({
                url: '?c=programar&a=programarInstructor',
                method: 'POST',
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function(response) {
                    var result = JSON.parse(response);
                    alert(result.message);
                    if (result.message === 'Lo logre putos') {
                        $('#programarModal').modal('hide');
                        calendar.refetchEvents();
                    }
                }
            });
        });
    });
</script>

<!-- Modal -->
<div class="modal fade" id="programarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Programar al Instructor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sus días seleccionados son los siguientes: </p>
                <p><span id="dias_a_programar"></span></p>
                <form id="programar-form">
                    <div class="mb-3 position-relative">
                        <div id="selected"></div>
                        <label for="instructor-search" class="form-label">Buscar Instructor</label>
                        <input id="select-value" type="hidden">
                        <div class="select-container">
                            <div class="select-wrapper">
                                <div class="select-input">
                                    <div id="select-selected">

                                    </div>
                                    <textarea id="select-input"></textarea>
                                </div>
                                <div id="select-list">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="resultadoAprendizaje" class="form-label">Resultado de Aprendizaje</label>
                        <input type="text" class="form-control" id="resultadoAprendizaje">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>
                        <button type="submit" id="select-send" class="btn btn-primary">PROGRAMAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
