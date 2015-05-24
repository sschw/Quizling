 <div id="canvasContainer">
<canvas id="quiz" width="600" height="500"></canvas>
</div>
<script type="text/javascript">
// Define text from config- and languagefile as js variables
var txt_basepath = "{#basepath#}";
var txt_language = "{$smarty.session.language}";

var txt_start = "{#txt_start#}";
var txt_back = "{#txt_back#}";
var txt_highscore = "{#txt_highscore#}";

var txt_rank = "{#txt_rank#}";
var txt_categories = "{#txt_categories#}";
var txt_playername = "{#txt_playername#}";
var txt_calcScore = "{#txt_calcScore#}";
var txt_score = "{#txt_score#}";
var txt_date  = "{#txt_date#}";
var txt_duration = "{#txt_duration#}";

var txt_selectCategory = "{#txt_selectCategory#}";
var txt_notEnoughQuestions = "{#txt_notEnoughQuestions#}";

var txt_time = "{#txt_time#}";
var txt_points = "{#txt_points#}";
var txt_userGotCorrect = "{#txt_userGotCorrect#}";
var txt_questions = "{#txt_questions#}";
var txt_joker = "{#txt_joker#}";
var txt_correct = "{#txt_correct#}";
var txt_wrong = "{#txt_wrong#}";
var txt_finish = "{#txt_finish#}";
var txt_saveIntoHighscore = "{#txt_saveIntoHighscore#}";
var txt_ignore = "{#txt_ignore#}";
var txt_rightAnswerWouldBe = "{#txt_rightAnswerWouldBe#}";

</script>
<script src="lib/createjs/quiz.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
function toggleMute() {
  sessionStorage.muteSound = (sessionStorage.muteSound == 'true') ? 'false' : 'true';
  createjs.Sound.setMute(!createjs.Sound.getMute());
}

$(document).ready(function() {
  createjs.Sound.setMute((sessionStorage.muteSound == 'true') ? true : false);
  $("#muteSound").click(toggleMute);
});
{/literal}
</script>
<span id="muteSound" style="margin-left: 80px; font-size: 10px; cursor: pointer;">{#txt_toggleSound#}</span><br><br>
<a style="margin-left: 80px; color: light-gray; font-size: 10px;" href="credits">{#txt_credits#}</a>