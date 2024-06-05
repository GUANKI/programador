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
        <a class="btn btn-success btn-lg mt-3" href="#">Programar</a>
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
        } else {
            alert("Ingrese un número de ficha.");
        }
    });
});

</script>