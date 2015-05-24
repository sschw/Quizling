<base href="{#basepath#}">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{#sitename#} - {#txt_title#}</title>
<link href="data:image/x-icon;base64,AAABAAEAEBAAAAAAAABoBQAAFgAAACgAAAAQAAAAIAAAAAEACAAAAAAAAAEAAAAAAAAAAAAAAAEAAAAAAAAAAAAAk5OTAD09PQDQ0NAAJCQkAAICAgDJyckAampqAEhISABjY2MABAQEADg4OADLy8sASkpKADExMQAPDw8ATExMACoqKgAICAgATk5OAOHh4QCLi4sAAQEBAGlpaQAKCgoAAwMDADc3NwBAQEAASUlJAAUFBQA5OTkAqqqqAEtLSwDe3t4AZmZmAAcHBwDOzs4AIiIiAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHwUZGQUGAAAAAAAAAAAABxYWBAAAAAAAAAAAAAAAABkWIwAAAAAAAAAAAAAVBRgJIh0WAQAAAAAAAAALGRYUAAAhAAUaAAAAAAADGQAWAAAAAB0AGQMAAAAADwUWJQAAAAARBQUPAAAAABYAFggAAAAAIAAFAAAAAAAWAAATAAAAABAAFgAAAAAABQUAGwAAAAACFgUFAAAAABcZACMAAAAAIwAZFwAAAAAAFhkZAAAAABkZFgAAAAAAAAASBQ4AAB4FEgAAAAAAAAAAAAwNBQocJAAAAAAAAAAAAAAAAAAAAAAAAAAAAP//AAD/AwAA/h8AAP4/AADwDwAA4YcAAMPDAADDwwAAw8MAAMPDAADDwwAAw8MAAOPHAADxjwAA+B8AAP//AAA=" rel="icon" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" media="screen" href="lib/external/jqgrid/css/ui.jqgrid.css">
<link rel="stylesheet" type="text/css" media="screen" href="lib/external/jqueryui/jquery-ui.min.css" />
<script src="lib/external/jquery.js" type="text/javascript"></script>
<script src="lib/external/jqueryui/jquery-ui.min.js" type="text/javascript"></script>
<script src="lib/external/easeljs.js" type="text/javascript"></script>
<script src="lib/external/tweenjs.js" type="text/javascript"></script>
<script src="lib/external/soundjs.js" type="text/javascript"></script>
<script src="lib/external/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="lib/external/jqgrid/js/i18n/grid.locale-{$smarty.session.language}.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
//all internal links are handled by ajax
$.expr[':'].external = function (a) {
  var PATTERN_FOR_EXTERNAL_URLS = /^(\w+:)?\/\//;
  var href = $(a).attr('href');
  return href !== undefined && href.search(PATTERN_FOR_EXTERNAL_URLS) !== -1;
};

$.expr[':'].internal = function (a) {
  return $(a).attr('href') !== undefined && !$.expr[':'].external(a);
};
  
$(document).ready(function(){


  $('body').on("click", "a:internal", function (e) {
    e.preventDefault();
    if($(this).attr("href") == window.location.href.replace({/literal}"{#basepath#}"{literal}, "./")) {
      location.reload();
    } else {
      var request = $.ajax({
        url: $(this).attr("href"),
        type: "GET",
        dataType: "html",
        beforeSend: function(xhr) {
          xhr.setRequestHeader("Ajax-Request", "true");
        }
      });
  
      request.done(function(msg) {
        $('#content').html(msg);
        $('audio').remove();
        createjs.Sound.stop();
        history.pushState("", e.target.textContent, e.target.href);
      });
  
      request.fail(function(code, text, msg) {
        if(msg == "Not Found") {
          $('#content').html("<h1>{/literal}{#txt_sitenotfound#}{literal}</h1><p>{/literal}{#txt_nositefound#}{literal}</p>");
          createjs.Sound.stop();
          history.pushState("", e.target.textContent, e.target.href);
        }
      });
    }
  });

  $(window).bind("popstate", function(e) {
    var request = $.ajax({
      url: window.location.href,
      type: "GET",
      dataType: "html",
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Ajax-Request", "true");
      }
    });

    request.done(function(msg) {
      $('#content').html(msg);
      createjs.Sound.stop();
      $('audio').remove();
    });
  });
});
</script>
{/literal}

