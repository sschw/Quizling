<div class="language">de</div><div class="language">en</div>
<script type="text/javascript">
$(document).ready(function(){
  $(".language").click(function(e) {
    var request = $.ajax({
      url: "./changeLanguage.php?language=" + $(this).text(),
      type: "GET"
    });
    request.done(function(msg) {
      location.reload(true);
    });
  });
});
</script>