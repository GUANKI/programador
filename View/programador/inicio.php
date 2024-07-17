<style>
    .fc-highlight {
        background-color: #ffc107 !important;
    }

    .fc-selected-date {
        background-color: rgba(255, 193, 7, 0.5) !important;
        /* Un amarillo más opaco */
    }

    #result p {
        cursor: pointer;
        padding: 5px;
        border-bottom: 1px solid #ccc;
    }

    #result p:hover {
        background-color: #f0f0f0;
    }

    /* .fc-event-title {
        white-space: normal !important;
        word-wrap: break-word !important;

    } */
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
                    var date = info.start;
                    var dateString = date.toISOString().split('T')[0];

                    if (!selectedDates.some(d => d.toISOString().split('T')[0] === dateString)) {
                        selectedDates.push(date);

                        var formattedDates = selectedDates.map(date => date.toLocaleDateString('es-ES', {
                            day: 'numeric',
                            month: 'long'
                        }));

                        // Añadir la clase de estilo al día seleccionado
                        var dayEl = document.querySelector(`.fc-day[data-date="${dateString}"]`);
                        if (dayEl) {
                            dayEl.classList.add('fc-selected-date');
                        }
                    }
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
                    $('#event-id').text(extendedProps.id); // Almacena el ID del evento
                    $('#infoModal').modal('show');
                }
            });

            calendar.render();

            $('#search-button').click(function() {
                var ficha = $('#ficha').val();
                if (ficha) {
                    clearSelectedDates();
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

                function sendRequest(data, force = false) {
                    if (force) {
                        data.force = true;
                    }

                    $.ajax({
                        url: '?c=programar&a=programarInstructor',
                        method: 'POST',
                        data: JSON.stringify(data),
                        contentType: "application/json",
                        success: function(response) {
                            try {
                                var result = JSON.parse(response);
                                if (result.confirm) {
                                    Swal.fire({
                                        title: 'Confirmación',
                                        html: result.message,
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Programar de todas formas',
                                        cancelButtonText: 'No programar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            sendRequest(data, true);
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Resultado',
                                        text: result.message,
                                        icon: result.type ? result.type : 'success'
                                    });
                                    if (result.message === 'Instructor programado exitosamente.') {
                                        $('#programarModal').modal('hide');
                                        calendar.refetchEvents();
                                        clearSelectedDates(); // Limpiar los días seleccionados después de programar
                                    }
                                }
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                                console.error('Response:', response);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('AJAX error:', textStatus, errorThrown);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al enviar la solicitud. Por favor, inténtalo de nuevo.',
                                icon: 'error'
                            });
                        }
                    });
                }

                sendRequest(data);
            });




            // Mostrar/ocultar campos de horarios personalizados
            $('input[name="jornada"]').change(function() {
                if ($(this).val() === 'personalizada') {
                    $('#horarioPersonalizado').show();
                } else {
                    $('#horarioPersonalizado').hide();
                }
            });

            // $('#programar-form').submit(function(event) {
            //     event.preventDefault();
            //     const {
            //         value
            //     } = document.querySelector("#select-value");
            //     var resultadoAprendizaje = $('#resultadoAprendizaje').val();
            //     var ficha = $('#ficha').val();
            //     var jornada = $('input[name="jornada"]:checked').val();
            //     var horaInicio = $('#horaInicio').val();
            //     var horaFin = $('#horaFin').val();

            //     var dates = selectedDates.map(date => date.toISOString().split('T')[0]);

            //     var data = {
            //         instructores: value,
            //         resultado_aprendizaje: resultadoAprendizaje,
            //         ficha: ficha,
            //         selectedDates: dates,
            //         jornada: jornada,
            //         horaInicio: horaInicio,
            //         horaFin: horaFin
            //     };

            //     function sendRequest(data, force = false) {
            //         if (force) {
            //             data.force = true;
            //         }

            //         $.ajax({
            //             url: '?c=programar&a=programarInstructor',
            //             method: 'POST',
            //             data: JSON.stringify(data),
            //             contentType: "application/json",
            //             success: function(response) {
            //                 try {
            //                     var result = JSON.parse(response);
            //                     if (result.confirm) {
            //                         Swal.fire({
            //                             title: 'Confirmación',
            //                             text: result.message,
            //                             icon: 'warning',
            //                             showCancelButton: true,
            //                             confirmButtonText: 'Programar de todas formas',
            //                             cancelButtonText: 'No programar'
            //                         }).then((result) => {
            //                             if (result.isConfirmed) {
            //                                 sendRequest(data, true);
            //                             }
            //                         });
            //                     } else {
            //                         Swal.fire({
            //                             title: 'Resultado',
            //                             text: result.message,
            //                             icon: result.success ? 'success' : 'error'
            //                         });
            //                         if (result.message === 'Instructor programado exitosamente.') {
            //                             $('#programarModal').modal('hide');
            //                             calendar.refetchEvents();
            //                             clearSelectedDates();
            //                         }
            //                     }
            //                 } catch (e) {
            //                     console.error('Error parsing JSON response:', e);
            //                     console.error('Response:', response);
            //                     Swal.fire({
            //                         title: 'Error',
            //                         text: 'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.',
            //                         icon: 'error'
            //                     });
            //                 }
            //             },
            //             error: function(jqXHR, textStatus, errorThrown) {
            //                 console.error('AJAX error:', textStatus, errorThrown);
            //                 Swal.fire({
            //                     title: 'Error',
            //                     text: 'Ocurrió un error en la comunicación con el servidor. Por favor, inténtalo de nuevo.',
            //                     icon: 'error'
            //                 });
            //             }
            //         });
            //     }

            //     sendRequest(data);
            // });




            function clearSelectedDates() {
                selectedDates.forEach(date => {
                    var dateString = date.toISOString().split('T')[0];
                    var dayEl = document.querySelector(`.fc-day[data-date="${dateString}"]`);
                    if (dayEl) {
                        dayEl.classList.remove('fc-selected-date');
                    }
                });
                selectedDates = [];
            }

            // Limpiar datos al cerrar el modal de programación
            $('#programarModal').on('hidden.bs.modal', function() {
                clearSelectedDates();
                $('#dias_a_programar').html('');
                $('#select-value').val('');
                $('#select-input').val('');
                $('#resultadoAprendizaje').val('');
                $('input[name="jornada"]').prop('checked', false);
                $('#horaInicio').val('');
                $('#horaFin').val('');
                $('#horarioPersonalizado').hide();
            });

            // Manejar la eliminación del evento
            $('#delete-event').on('click', function() {
                var eventId = $('#event-id').text();

                Swal.fire({
                    title: '¿Está seguro?',
                    text: "No podrás revertir esto",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '?c=programar&a=eliminarEvento',
                            method: 'POST',
                            data: {
                                id: eventId
                            },
                            success: function(response) {
                                // Manejar la respuesta del servidor
                                try {
                                    var result = JSON.parse(response);
                                    Swal.fire(
                                        'Eliminado!',
                                        result.message,
                                        'success'
                                    );
                                    $('#infoModal').modal('hide');
                                    calendar.refetchEvents();
                                } catch (e) {
                                    console.error('Error parsing JSON response:', e);
                                    console.error('Response:', response);
                                    Swal.fire(
                                        'Error!',
                                        'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.',
                                        'error'
                                    );
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('AJAX error:', textStatus, errorThrown);
                                Swal.fire(
                                    'Error!',
                                    'Ocurrió un error en la comunicación con el servidor. Por favor, inténtalo de nuevo.',
                                    'error'
                                );
                            }
                        });
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
                        calendar.refetchEvents();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
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
                    <p><strong>Resultado:</strong> <span id="infoResultado"></span></p>
                    <p><strong>Instructor:</strong> <span type="text" id="infoInstructor"></span></p>
                    <p><strong>Id del modulo:</strong> <span id="event-id"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="delete-event">Eliminar</button>
                    <!-- <button type="button" class="btn btn-primary" id="modify-event">Modificar</button> -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>