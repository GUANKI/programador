</head>
<body>
    <div class="container mb-5">
        <h1 class="my-4">Consultar Programación</h1>
        <form id="search-form" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" id="ficha" name="ficha" placeholder="Ingrese el número de ficha">
                <button type="button" class="btn btn-success" id="search-button">Buscar</button>
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
                selectable: false,
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
        });
    </script>

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
                    <p><strong>Instructor:</strong> <span id="infoInstructor"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>