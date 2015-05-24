<h1>{#txt_login#}</h1>
<form class="loginForm" action="./login" method="post">
  <div>
    <div style="width: 100px; float: left; margin-top: 12px;">{#txt_username#}: </div><div><input name="username" type="text" maxlength="30" required></div>
    <div style="width: 100px; float: left; margin-top: 12px;">{#txt_password#}: </div><div><input name="password" type="password" maxlength="30" required></div>
    <input type="submit" value="{#txt_login#}">
  </div>
</form>