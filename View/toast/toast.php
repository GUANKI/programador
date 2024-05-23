<?php

if (isset($_COOKIE["notificacion"])) {
  [$var1,$var2] = explode("-",$_COOKIE["notificacion"])
?>
  <div role="alert" aria-live="assertive" aria-atomic="true" class="toast show position-fixed end-0 bottom-0 m-3" data-bs-autohide="false">
    <div class="toast-header <?php if($var1 == "Error"){
      echo "bg-danger";
    }else if ($var1 == "Exito"){
      echo "bg-success";
    }  ?>">
      <!-- <img src="..." class="rounded me-2" alt="..."> -->
      <strong class="me-auto"><?php echo $var1 ?></strong>
      <small> Hace: <span id="time-value">0</span> <span id="time-text">sec</span></small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body"> <?= $var2; ?>
    </div>
  </div>

<?php
}

?>
<script>
  if (document.querySelector(".toast")) {
    var segundos = 0;
    const timeValue = document.querySelector("#time-value")
    const timeText = document.querySelector("#time-text")
    setInterval(function() {
      if (segundos < 60) {

        timeValue.innerHTML = segundos;
        timeText.innerHTML = "sec"
      } else if (segundos < 3600) {
        timeValue.innerHTML = parseInt(segundos / 60)
        timeText.innerHTML = "min"
      } else {
        timeValue.innerHTML = parseInt(segundos / 3600)
        timeText.innerHTML = "hrs"
      }

      segundos++;
    }, 1000)
  }
</script>