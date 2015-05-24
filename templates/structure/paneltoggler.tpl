<img style="border: 1px solid black; width: 32px; height: 32px;" src="img/panel_show.png" alt="&#9776;">
{literal}
<script type="text/javascript">
function togglePanel() {
  sessionStorage.showPanel = (sessionStorage.showPanel == 'true') ? 'false' : 'true';
  if(sessionStorage.showPanel == 'true') {
    $('#panel').fadeIn();
    $(this).attr("src", "img/panel_hide.png");
    $(this).css("border", "1px solid white");
  } else {
    $('#panel').fadeOut();
    $(this).attr("src", "img/panel_show.png");
    $(this).css("border", "1px solid black");
  }
}

$(document).ready(function() {
  $('#paneltoggler img').click( togglePanel );
  $('#paneltoggler img').load( function() {
    if(sessionStorage.showPanel == 'true' && $(this).attr("src") != "img/panel_hide.png") {
      $('#panel').fadeIn();
      $(this).attr("src", "img/panel_hide.png");
      $(this).css("border", "1px solid white");
    }
  } );
});
</script>
{/literal}