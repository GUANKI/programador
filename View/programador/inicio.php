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
</head>

<body>
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

    <script src="path/to/jquery.min.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>
    <script src="path/to/fullcalendar/main.js"></script>
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
                        month: 'long'
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
                },
                eventClick: function(info) {
                    var event = info.event;
                    var extendedProps = event.extendedProps;

                    $('#infoFecha').text(event.start.toLocaleDateString('es-ES', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }));
                    $('#infoHoras').text(event.start.toLocaleTimeString('es-ES', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' - ' + event.end.toLocaleTimeString('es-ES', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }));
                    $('#infoFicha').text(extendedProps.ficha);
                    $('#infoResultado').text(extendedProps.resultado_aprendizaje);
                    $('#infoInstructor').text(extendedProps.instructor_nombre);
                    $('#event-id').val(event.id); // Almacena el ID del evento
                    $('#infoModal').modal('show');
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

            // Mostrar/ocultar campos de horarios personalizados
            $('input[name="jornada"]').change(function() {
                if ($(this).val() === 'personalizada') {
                    $('#horarioPersonalizado').show();
                } else {
                    $('#horarioPersonalizado').hide();
                }
            });

            $('#programar-form').submit(function(event) {
                event.preventDefault();
                const {
                    value
                } = document.querySelector("#select-value");
                var resultadoAprendizaje = $('#resultadoAprendizaje').val();
                var ficha = $('#ficha').val();
                var jornada = $('input[name="jornada"]:checked').val();
                var horaInicio = $('#horaInicio').val();
                var horaFin = $('#horaFin').val();

                var dates = selectedDates.map(date => date.toISOString().split('T')[0]);

                var data = {
                    instructores: value,
                    resultado_aprendizaje: resultadoAprendizaje,
                    ficha: ficha,
                    selectedDates: dates,
                    jornada: jornada,
                    horaInicio: horaInicio,
                    horaFin: horaFin
                };

                $.ajax({
                    url: '?c=programar&a=programarInstructor',
                    method: 'POST',
                    data: JSON.stringify(data),
                    contentType: "application/json",
                    success: function(response) {
                        try {
                            var result = JSON.parse(response);
                            alert(result.message);
                            if (result.message === 'Instructor programado exitosamente.') {
                                $('#programarModal').modal('hide');
                                calendar.refetchEvents();
                            }
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                            console.error('Response:', response);
                            alert('Ocurrió un error inesperado. Por favor, inténtalo de nuevo.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX error:', textStatus, errorThrown);
                        alert('Ocurrió un error en la comunicación con el servidor. Por favor, inténtalo de nuevo.');
                    }
                });
            });
        });

        $(document).ready(function() {
    // Cuando se hace clic en un evento del calendario
    $('#calendar').on('eventClick', function(info) {
        // Rellenar el modal con la información del evento
        $('#infoFecha').text(info.event.start.toLocaleDateString());
        $('#infoHoras').text(info.event.start.toLocaleTimeString() + ' - ' + info.event.end.toLocaleTimeString());
        $('#infoFicha').text(info.event.extendedProps.ficha);
        $('#infoResultado').val(info.event.extendedProps.resultado);
        $('#infoInstructor').val(info.event.extendedProps.instructor);
        $('#event-id').val(info.event.id); // Almacena el ID del evento

        // Mostrar el modal
        $('#infoModal').modal('show');
    });

    // Manejar la eliminación del evento
    $('#delete-event').on('click', function() {
        var eventId = $('#event-id').val();

        $.ajax({
            url: '?c=programar&a=eliminarEvento',
            method: 'POST',
            data: { id: eventId },
            success: function(response) {
                // Manejar la respuesta del servidor
                alert(response.message);
                $('#infoModal').modal('hide');
                // Actualizar el calendario
                $('#calendar').fullCalendar('removeEvents', eventId);
            },
            error: function() {
                alert('Error al eliminar el evento.');
            }
        });
    });

    // Manejar la modificación del evento
    $('#modify-event').on('click', function() {
        var eventId = $('#event-id').val();
        var resultado = $('#infoResultado').val();
        var instructor = $('#infoInstructor').val();
        console.log(eventId);
        $.ajax({
            url: '?c=programar&a=modificarEvento',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                id: eventId,
                resultado_aprendizaje: resultado,
                instructor_nombre: instructor
            }),
            success: function(response) {
                // Manejar la respuesta del servidor
                alert(response.message);
                $('#infoModal').modal('hide');
                // Actualizar el calendario
                // Aquí debes actualizar el evento en el calendario con los nuevos datos
            },
            error: function() {
                alert('Error al modificar el evento.');
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
                                        <div id="select-selected"></div>
                                        <textarea id="select-input"></textarea>
                                    </div>
                                    <div id="select-list"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="resultadoAprendizaje" class="form-label">Resultado de Aprendizaje</label>
                            <input type="text" class="form-control" id="resultadoAprendizaje">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Seleccione Jornada:</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jornada" id="jornadaMañana" value="mañana">
                                    <label class="form-check-label" for="jornadaMañana">Mañana (06:00 - 11:59)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jornada" id="jornadaTarde" value="tarde">
                                    <label class="form-check-label" for="jornadaTarde">Tarde (12:00 - 17:59)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jornada" id="jornadaNoche" value="noche">
                                    <label class="form-check-label" for="jornadaNoche">Noche (18:00 - 23:00)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jornada" id="jornadaPersonalizada" value="personalizada">
                                    <label class="form-check-label" for="jornadaPersonalizada">Personalizada</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" id="horarioPersonalizado" style="display:none;">
                            <label for="horaInicio" class="form-label">Hora de Inicio:</label>
                            <input type="time" class="form-control" id="horaInicio">
                            <label for="horaFin" class="form-label">Hora de Fin:</label>
                            <input type="time" class="form-control" id="horaFin">
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

    <!-- Modal Informativo -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="infoModalLabel">Información del Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Día del Evento:</strong> <span id="infoFecha"></span></p>
                <p><strong>Rango de Horas:</strong> <span id="infoHoras"></span></p>
                <p><strong>Ficha:</strong> <span id="infoFicha"></span></p>
                <p><strong>Resultado:</strong> <textarea id="infoResultado" class="form-control" rows="2"></textarea></p>
                <p><strong>Instructor:</strong> <input type="text" id="infoInstructor" class="form-control"></p>
                <input type="hidden" id="event-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete-event">Eliminar</button>
                <button type="button" class="btn btn-primary" id="modify-event">Modificar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
