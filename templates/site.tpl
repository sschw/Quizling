{config_load file="config.conf"}
{config_load file="language_{$smarty.session.language}.conf"}
<!DOCTYPE html>
<html>
  <head>
    {include file="structure/head.tpl"}
  </head>
  <body>
    <div id="paneltoggler">{include file="structure/paneltoggler.tpl"}</div>
    <div id="panel" style="display: none;">
      {include file="structure/panel.tpl"}
    </div>
    <div id="page">
      <div id="header">
        {include file="structure/header.tpl"}
      </div>
      <div id="content">
        {include file="{$page}.tpl"}
      </div>
      <div id="footer">
        {include file="structure/footer.tpl"}
      </div>
    </div>
    <div id="siteFooter">{include file="structure/siteFooter.tpl"}</div>
  </body>
</html>