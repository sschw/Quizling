{if isset($smarty.session.user_id)}
  <ul style="float: right">
    <li><a href="./admin" style="color: white; text-decoration: none;">{#txt_toTheAdminOverview#}</a></li>
    <li class="logout">{#txt_logout#}</li>
  </ul>
  <span>{#txt_welcome#}</span>
  
  {literal}
  <script type="text/javascript">
    $(document).ready(function(){
      $(".logout").click(function(e) {
        e.preventDefault();
        var request = $.ajax({
          url: "./logout.php",
          type: "POST",
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Ajax-Request", "true");
          }
        });
        request.done(function(msg) {
          location.reload(true);
        });
      });
    });
  </script>
  {/literal}
{else}
  <form class="loginForm" action="./login" method="post">
    <span>{#txt_username#}:</span>
    <input name="username" type="text" maxlength="30" required>
    <span>{#txt_password#}:</span>
    <input name="password" type="password" maxlength="30" required>
    <input type="submit" value="{#txt_login#}">
  </form>
  {literal}
  <script type="text/javascript">
    $(document).ready(function(){
      $("body").append('<div id="login-dialog" style="display: none;"><p>Bitte überprüfen Sie Ihre Eingaben.</p></div>');
      $(".loginForm").submit(function(e) {
        e.preventDefault();
        var request = $.ajax({
          url: "./login.php",
          type: "POST",
          data: $(this).serialize(),
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Ajax-Request", "true");
          }
        });
        
        request.done(function(msg) {
          if(msg == "true") {
            location.reload(true);
          } else {
            $("#login-dialog").dialog({
              modal: true,
              title: "Login fehlgeschlagen!",
              button: {
                Ok: function() {
                  $(this).remove();
                  $("#login-dialog").dialog( "close" );
                }
              }
            });
          }
        });
      });
    });
  </script>
  {/literal}
{/if}