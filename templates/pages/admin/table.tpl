{if isset($details) && ($details == "questions" || $details == "categories" || $details == "highscore")}
<script type="text/javascript">
{literal}
function init_questions() {
{/literal}
  $colNames = ['ID','{#txt_category#}','{#txt_question#}', '{#txt_correctAnswer#}', '{#txt_wrongAnswer#} 1','{#txt_wrongAnswer#} 2','{#txt_wrongAnswer#} 3','{#txt_numberOf#} {#txt_correctAnswers#}','{#txt_numberOf#} {#txt_wrongAnswers#}'];
  $title = "{#txt_questions#}";
{literal}
  $colModel = [
       		{name:'id',index:'id', width:50,editable:false,editoptions:{readonly:true,size:10}},
       		{name:'category',index:'category', width:80,editable:true},
       		{name:'text',index:'text', width:300,editable:true},
       		{name:'correct_answer',index:'correct_answer', width:200, editable:true},
       		{name:'wrong_answer1',index:'wrong_answer1', width:200, editable:true},		
       		{name:'wrong_answer2',index:'wrong_answer2', width:200, editable:true},
      		{name:'wrong_answer3',index:'wrong_answer3',width:200, editable:true},
      		{name:'number_of_correct',index:'number_of_correct',width:100, editable: false},
       		{name:'number_of_wrong',index:'number_of_wrong', width:100,editable: false}		
       	];
  $deleteonly = false;
}
function init_categories() {
{/literal}
  $colNames = ['ID','{#txt_name#}'];
  $title = "{#txt_categories#}";
{literal}
  $colModel = [
       		{name:'id',index:'id', width:50,editable:false,editoptions:{readonly:true,size:10}},
       		{name:'name',index:'name', width:200,editable:true}		
       	];
  $deleteonly = false;
}
function init_highscore() {
{/literal}
  $colNames = ['ID','{#txt_categories#}', '{#txt_playername#}', '{#txt_score#}', '{#txt_date#}', '{#txt_duration#}'];
  $title = "{#txt_highscore#}";
{literal}
  $colModel = [
       		{name:'id',index:'id', width:50,editable:false,editoptions:{readonly:true,size:10}},
       		{name:'categories',index:'categories', width:200,editable:true},
       		{name:'playername',index:'playername', width:200,editable:true},
       		{name:'score',index:'score', width:150,editable:true},
       		{name:'time',index:'time', width:100,editable:false},
       		{name:'duration',index:'duration', width:100,editable:false}
       	];
  $deleteonly = true;
}
{/literal}
</script>
<script type="text/javascript">
{literal}
$(document).ready(function() {
{/literal}
  init_{$details}();
{literal}
  $("#adminTable").jqGrid({
     	url:'adminload.php?type={/literal}{$details}{literal}',
  	  datatype: "json",
     	colNames:$colNames,
     	colModel:$colModel,
     	rowNum:100,
     	rowList:[100,200,300],
     	pager: '#padminTable',
     	sortname: 'id',
      viewrecords: true,
      sortorder: "desc",
      caption:$title,
      editurl:"adminedit.php?type={/literal}{$details}{literal}",
  	height:210
  });
  $("#adminTable").jqGrid('navGrid','#padminTable',{edit:false,add:false,del:true},{},{},{},{sopt:['eq','ne','lt','gt','bw','ew','cn']},{});
  var editOptions = {
      keys: true,
      successfunc: function () {
          var $self = $(this);
          setTimeout(function () {
              $self.trigger("reloadGrid");
          }, 50);
      }
  };
  if(!$deleteonly) {
    $("#adminTable").jqGrid('inlineNav', '#padminTable', { addParams: { position: "last", addRowParams: editOptions }, editParams: editOptions});
  }

});
{/literal}
</script>
{if $details == "categories"}
<h1>{#txt_categories#}</h1>
<ul style="padding: 0;">
  <li class="adminNavigation"><a href="./admin">{#txt_toTheAdminOverview#}</a></li>
  <li class="adminNavigation"><a href="admin/table/categories">{#txt_categories#}</a></li>
  <li class="adminNavigation"><a href="admin/table/questions">{#txt_questions#}</a></li>
  <li class="adminNavigation"><a href="admin/table/highscore">{#txt_highscore#}</a></li>
</ul>
<p>{#txt_table_descr_categories#}</p>
{elseif $details == "highscore"}
<h1>{#txt_highscore#}</h1>
<ul style="padding: 0;">
  <li class="adminNavigation"><a href="./admin">{#txt_toTheAdminOverview#}</a></li>
  <li class="adminNavigation"><a href="admin/table/categories">{#txt_categories#}</a></li>
  <li class="adminNavigation"><a href="admin/table/questions">{#txt_questions#}</a></li>
  <li class="adminNavigation"><a href="admin/table/highscore">{#txt_highscore#}</a></li>
</ul>
<p>{#txt_table_descr_highscore#}</p>
{elseif $details == "questions"}
<h1>{#txt_questions#}</h1>
<ul style="padding: 0;">
  <li class="adminNavigation"><a href="./admin">{#txt_toTheAdminOverview#}</a></li>
  <li class="adminNavigation"><a href="admin/table/categories">{#txt_categories#}</a></li>
  <li class="adminNavigation"><a href="admin/table/questions">{#txt_questions#}</a></li>
  <li class="adminNavigation"><a href="admin/table/highscore">{#txt_highscore#}</a></li>
</ul>
<p>{#txt_table_descr_questions#}</p>
{/if}



<table id="adminTable"></table>
<div id="padminTable"></div>
{else}
<h3>{#txt_sitenotfound#}</h3>
<p>{#txt_nositefound#}</p>
<a href="admin" style="color: black;">{#txt_backToOverview#}</a>
{/if}