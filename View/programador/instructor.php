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
    <h1 class="my-4">Consultar Programación de Instructores</h1>
    <form id="search-form" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" id="instructor" name="instructor" placeholder="Ingrese el nombre del instructor">
            <button type="button" class="btn btn-primary" id="search-button">Buscar</button>
        </div>
    </form>
    <div id='calendar'></div>
</div>

<script>
    $(document).ready(function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: function(fetchInfo, successCallback, failureCallback) {
                var instructor = document.getElementById('instructor').value;
                if (instructor) {
                    fetch(`?c=programar&a=getInstructorEvents&instructor=${instructor}`)
                        .then(response => response.json())
                        .then(events => successCallback(events))
                        .catch(error => failureCallback(error));
                }
            },
            eventClick: function(info) {
                var eventObj = info.event;
                var startDate = eventObj.start.toLocaleDateString('es-ES');
                var endDate = eventObj.end ? eventObj.end.toLocaleDateString('es-ES') : startDate;
                var startTime = eventObj.start.toLocaleTimeString('es-ES');
                var endTime = eventObj.end ? eventObj.end.toLocaleTimeString('es-ES') : 'No especificado';

                $('#modal-title').text(`Evento: ${eventObj.title}`);
                $('#event-info').html(`
                    <p><strong>Fecha de inicio:</strong> ${startDate}</p>
                    <p><strong>Fecha de fin:</strong> ${endDate}</p>
                    <p><strong>Hora de inicio:</strong> ${startTime}</p>
                    <p><strong>Hora de fin:</strong> ${endTime}</p>
                    <p><strong>Ficha:</strong> ${eventObj.extendedProps.ficha}</p>
                    <p><strong>Resultado de Aprendizaje:</strong> ${eventObj.extendedProps.resultadoAprendizaje}</p>
                    <p><strong>Instructor:</strong> ${eventObj.extendedProps.instructor}</p>
                `);
                $('#eventModal').modal('show');
            }
        });

        calendar.render();

        $('#search-button').click(function() {
            var instructor = $('#instructor').val();
            if (instructor) {
                calendar.refetchEvents();
            } else {
                alert("Ingrese el nombre del instructor.");
            }
        });
    });
</script>

<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-title"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="event-info">
                <!-- Información del evento se mostrará aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
