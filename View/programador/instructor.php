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

    .fc-event-title {
    white-space: normal !important; /* Permitir que el texto se ajuste en múltiples líneas */
    word-wrap: break-word !important; /* Permitir que las palabras largas se rompan y ajusten en la siguiente línea */
}
</style>
</head>

<body>
    <div class="container mb-4">
        <h1 class="my-4">Buscar Instructor</h1>
        <div class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" id="instructor-search" placeholder="Ingrese el nombre del instructor">
                <div id="result" class="list-group"></div>
            </div>
        </div>
        <div id='calendar'></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                events: function(fetchInfo, successCallback, failureCallback) {
                    var instructorId = $('#instructor-search').data('selected-instructor-id');
                    if (instructorId) {
                        fetch(`?c=programar&a=GetEvents2&instructorId=${instructorId}`)
                            .then(response => response.json())
                            .then(events => successCallback(events))
                            .catch(error => failureCallback(error));
                    } else {
                        successCallback([]); // Si no hay instructor seleccionado, no mostrar eventos
                    }
                },
                eventClick: function(info) {
                    var eventObj = info.event;
                    var modalHtml = `
                    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eventModalLabel">Detalle del Evento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Ficha:</strong> ${eventObj.extendedProps.ficha}</p>
                                    <p><strong>Resultado de Aprendizaje:</strong> ${eventObj.extendedProps.resultadoAprendizaje}</p>
                                    <p><strong>Instructor:</strong> ${eventObj.title.split(' - ')[1]}</p>
                                    <p><strong>Fecha:</strong> ${eventObj.start.toLocaleDateString('es-ES')}</p>
                                    <p><strong>Hora:</strong> ${eventObj.extendedProps.horaInicio} - ${eventObj.extendedProps.horaFin}</p>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    $('body').append(modalHtml);
                    $('#eventModal').modal('show');
                    $('#eventModal').on('hidden.bs.modal', function() {
                        $(this).remove();
                    });
                }
            });

            calendar.render();

            $('#instructor-search').on('input', function() {
                var query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: '?c=instructor&a=buscarinstructor',
                        data: {
                            search: query
                        },
                        success: function(data) {
                            var instructors = JSON.parse(data);
                            var resultHtml = instructors.map(i => `<a href="#" class="list-group-item list-group-item-action" data-instructor-id="${i.id}">${i.nombre}</a>`).join('');
                            $('#result').html(resultHtml).show();
                        }
                    });
                } else {
                    $('#result').hide();
                }
            });

            $('#result').on('click', 'a', function() {
                var instructorName = $(this).text();
                var instructorId = $(this).data('instructor-id');
                $('#instructor-search').val(instructorName).data('selected-instructor-id', instructorId);
                $('#result').hide();
                calendar.refetchEvents();
            });
        });
    </script>