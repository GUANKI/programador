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
        <button type="button" class="btn btn-success btn-lg" id="btn-programar" data-bs-toggle="modal" data-bs-target="#exampleModal">
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
                selectedDates.push(info.startStr);
                alert("Tus días seleccionados son: " + selectedDates.join(", "));
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
                $("#btn-programar").click(function() {
                    var formattedDates = selectedDates.map(dateStr => {
                        var date = new Date(dateStr);
                        return date.toLocaleDateString('es-ES', {
                            day: 'numeric',
                            month: 'long'
                        });
                    });
                    $("#dias_a_programar").html(formattedDates.join(", "));
                    $("#programarModal").modal("show");
                });
            } else {
                alert("Ingrese un número de ficha.");
            }
        });


    });
</script>

<!-- Modal -->
<div class="modal fade" id="programarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sus días seleccionados son los siguientes: <span id="dias_a_programar"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>