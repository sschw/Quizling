// Init App
var canvas = document.getElementById('quiz');
var stage = new createjs.Stage(canvas);
var c_background = new createjs.Container();
var c_content = new createjs.Container();
var soundBackground = null;

stage.enableMouseOver(10);

stage.addChild(c_background);
stage.addChild(c_content);

// Init Sound
createjs.Sound.registerPlugins([createjs.HTMLAudioPlugin, createjs.WebAudioPlugin, createjs.FlashPlugin]);
createjs.Sound.defaultInterruptBehavior = createjs.Sound.INTERRUPT_EARLY;
createjs.Sound.alternateExtensions = ["mp3", "m4a", "wav"];
createjs.Sound.registerSound("sound/menu.mp3", "menu");
createjs.Sound.registerSound("sound/menu_select.wav", "menuSelect");
createjs.Sound.registerSound("sound/menu_back.wav", "menuBack");
createjs.Sound.registerSound("sound/menu_error.wav", "menuError");
createjs.Sound.registerSound("sound/quiz.mp3", "quiz");
createjs.Sound.registerSound("sound/correct.ogg", "correct");
createjs.Sound.registerSound("sound/wrong.ogg", "wrong");
createjs.Sound.registerSound("sound/joker.ogg", "joker");
createjs.Sound.registerSound("sound/" + txt_language + "/welcome.mp3", "welcome");
createjs.Sound.registerSound("sound/" + txt_language + "/selectCategories.mp3", "selectCategories");

// Redraw every 60ms
createjs.Ticker.setFPS(60);
createjs.Ticker.addEventListener("tick", tick);

function tick(event) {
  stage.update();
}

initOpening();

// Beginning Stage - Animations
function initOpening() {
  var animationBackground = new createjs.Shape();
  animationBackground.graphics.beginFill("black").drawRect(0,0,600,600);
  c_background.addChild(animationBackground);

  var madeBy = new createjs.Text("Made by Sandro Schwager\n\nMKN151", "normal 42px Impact", "white");
  madeBy.alpha = 0;
  madeBy.x = 80;
  madeBy.y = 200;

  c_content.addChild(madeBy);
  stage.update();

  // Show Text and call Quiz-Background afterwards
  createjs.Tween.get(madeBy).to({alpha:1}, 2000).wait(500).to({alpha:0}, 2000).call(initBackground);
}

// Set Quiz Background, animate and open Menu
function initBackground() {
  var quizBackground = new createjs.Bitmap("img/quizbackground.jpg");
  quizBackground.alpha = 0;
  c_background.addChild(quizBackground);
  createjs.Tween.get(quizBackground).to({alpha:1}, 1000).call(deleteOldBackgroundAndOpenMenu);
  // Removes the old background and start the menu
  function deleteOldBackgroundAndOpenMenu() {
    c_background.removeChildAt(0);

    // Stop if user did a redirect on another page
    if(window.location.href.substr(window.location.href.length - 1) == '/') {
      if(window.location.href != txt_basepath && window.location.href != txt_basepath + "game/") {
        return;
      }
    } else {
      if(window.location.href + "/" != txt_basepath && window.location.href != txt_basepath + "game") {
        return;
      }
    }

    createjs.Sound.play("welcome");
    initMenuWithSound();
  }
}

// Start Menu And Background Sound
function initMenuWithSound() {
  if(soundBackground != null)
    soundBackground.stop();
  soundBackground = createjs.Sound.play("menu", {loop: -1});
  soundBackground.volume = 0.5;

  initMenu();
}

// Start Menu
function initMenu() {
  // GUI STUFF
  c_content.removeAllChildren();

  var title = new createjs.Bitmap("img/quizling_logo.png");
  title.x = -300;
  title.y = 50;
  c_content.addChild(title);

  createjs.Tween.get(title).to({x:150}, 1700, createjs.Ease.elasticOut);

  var startGame = createButton(txt_start, -200, 200, function(evt){
    // Change Menu to Category Selection on click
    var soundSelect = createjs.Sound.play("menuSelect");
    initCategorySelection();
  });
  c_content.addChild(startGame);
  createjs.Tween.get(startGame).wait(500).to({x:225}, 1700, createjs.Ease.elasticOut);

  var highscore = createButton(txt_highscore, -200, 270, function(evt) {
    // Change Menu to Highscore on click
    var soundSelect = createjs.Sound.play("menuSelect");
    initHighscore();
  });
  c_content.addChild(highscore);
  createjs.Tween.get(highscore).wait(1000).to({x:225}, 1700, createjs.Ease.elasticOut).call(function() {
    // After initialize Menu add some sweet animations :)
    var animatedTextl = new createjs.Text("?", "normal 46px Impact", "#ffffff");
    animatedTextl.x = 70;
    animatedTextl.y = -100;
    animatedTextl.rotation = 0;
    animatedTextl.textAlign = "center";
    animatedTextl.textBaseline = "middle";
    var animatedTextBackToTopl = function() {
      animatedTextl.y = -100;
      animatedTextl.rotation = 0;
    }
    c_content.addChild(animatedTextl);
    createjs.Tween.get(animatedTextl, {loop: true}).wait(500).to({y: 600, rotation: 500}, 2000, createjs.Ease.linear).call(animatedTextBackToTopl);

    var animatedTextr = new createjs.Text("?", "normal 46px Impact", "#ffffff");
    animatedTextr.x = 530;
    animatedTextr.y = -100;
    animatedTextr.rotation = 0;
    animatedTextr.textAlign = "center";
    animatedTextr.textBaseline = "middle";
    var animatedTextBackToTopr = function() {
      animatedTextr.y = -100;
      animatedTextr.rotation = 0;
    }
    c_content.addChild(animatedTextr);
    createjs.Tween.get(animatedTextr, {loop: true}).wait(500).to({y: 600, rotation: 500}, 2300, createjs.Ease.linear).call(animatedTextBackToTopr);
  });
}

// Category Selection
function initCategorySelection() {
  c_content.removeAllChildren();

  // load the available categories
  var categories = loadAjaxData("loadCategories.php");

  createjs.Sound.play("selectCategories");

  // GUI STUFF (Load a DOM Highscore and add it to the JS Library)
  var list = createSelectableList(categories, 460,350);
  list.x = -600;
  list.y = 30;
  c_content.addChild(list);
  createjs.Tween.get(list).to({x:70}, 1700, createjs.Ease.elasticOut);

  var back = createButton(txt_back, -200, 420, function(evt) {
    var soundSelect = createjs.Sound.play("menuBack");
    $("#selectableCategories").remove();
    initMenu();
  });
  c_content.addChild(back);
  createjs.Tween.get(back).wait(500).to({x:100}, 1700, createjs.Ease.elasticOut);

  var errText = new createjs.Text(txt_selectCategory, "normal 12px Impact", "#AA0000");
  errText.x = 425;
  errText.y = 400;
  errText.alpha = 0;
  errText.textAlign = "center";
  errText.textBaseline = "middle";
  c_content.addChild(errText);

  var starting = createButton(txt_start, -200, 420, function(evt) {
    errText.alpha = 0;
    // check if data are selected
    if($(".ui-selected").length > 0) {
      // get selected data from list
      var allSelectedCategories = $('.ui-selected').map(function(){ return $(this).text(); }).get();
      // try to init a new round
      if(loadAjaxDataWithData("initNewRound.php", {categories: JSON.stringify(allSelectedCategories)}) > 0) {
        var soundSelect = createjs.Sound.play("menuSelect");
        $("#selectableCategories").remove();
        initQuizMenu();
      } else {
        // show error message
        createjs.Sound.play("menuError");
        errText.text = txt_notEnoughQuestions;
        errText.alpha = 1;
      }
    } else {
      // show error message
      createjs.Sound.play("menuError");
      errText.text = txt_selectCategory;
      errText.alpha = 1;
    }
  });
  c_content.addChild(starting);
  createjs.Tween.get(starting).wait(500).to({x:350}, 1700, createjs.Ease.elasticOut);
}

// Question View
function initQuizMenu() {
  c_content.removeAllChildren();

  if(soundBackground != null)
    soundBackground.stop();
  soundBackground = createjs.Sound.play("quiz", {loop: -1});
  soundBackground.volume = 0.2;
  // GUI STUFF - Create Ingame Stats
  var time = new createjs.Text(txt_time + ": 0s", "bold 10px Verdana", "#000000");
  time.x = 0;
  time.y = 0;
  // autocount time
  time.time = 0;
  time.func = function() {
    time.time++;
    time.text = txt_time + ": " + time.time + "s";
    window.setTimeout(time.func, 1000)
  }
  window.setTimeout(time.func, 1000);
  /*addEventListener("tick", function() {
    // Start the clock
    time.time++;
    time.text = txt_time + ": " + Math.ceil(time.time/60) + "s";

  });*/
  var points = new createjs.Text(txt_points + ": 0", "bold 10px Verdana", "#000000");
  points.x = 0;
  points.y = 20;
  var nOQuestions = new createjs.Text(txt_questions + ": 0/0", "bold 10px Verdana", "#000000");
  nOQuestions.x = 0;
  nOQuestions.y = 40;
  nOQuestions.max = 0;
  nOQuestions.cur = 0;
  var statBox = new createjs.Container();
  statBox.x = 10;
  statBox.y = 10;
  statBox.alpha = 0;
  statBox.addChild(time);
  statBox.addChild(points);
  statBox.addChild(nOQuestions);

  c_content.addChild(statBox);
  createjs.Tween.get(statBox).to({alpha:1}, 1000);

  // GUI STUFF - init Question / Answer Gui
  var question = new createjs.Text("", "normal 26px Impact", "#000000");
  question.x = -600;
  question.y = 150;
  question.maxWidth = 400;
  question.textAlign = "center";
  question.textBaseline = "middle";
  c_content.addChild(question);

  var answer1 = createDetailedButton("", -300, 300, 200, 60, 12, function(){});
  c_content.addChild(answer1);
  var answer2 = createDetailedButton("", 900, 300, 200, 60, 12, function(){});
  c_content.addChild(answer2);
  var answer3 = createDetailedButton("", -300, 400, 200, 60, 12, function(){});
  c_content.addChild(answer3);
  var answer4 = createDetailedButton("", 900, 400, 200, 60, 12, function(){});
  c_content.addChild(answer4);
  
  // GUI STUFF - Last Question Stats
  var correctAnswered = new createjs.Text(txt_userGotCorrect + ": 0%", "bold 16px Verdana", "#000000");
  correctAnswered.x = 590;
  correctAnswered.y = 10;
  correctAnswered.alpha = 0;
  correctAnswered.textAlign = "right";

  c_content.addChild(correctAnswered);
  
  // GUI STUFF - Joker
  var joker = createDetailedButton(txt_joker, 550, 450, 50, 50, 12, function(){
    // Joker Click Handler
    // Hide Joker
    createjs.Tween.get(joker).to({alpha:0}, 1000);
    createjs.Sound.play("joker");
    // Handler for loading wrong answers and hide them
    var wAnswers = loadAjaxData("loadFFJoker.php");
    if(answer1.label.text == wAnswers[0] || answer1.label.text == wAnswers[1]) {
      createjs.Tween.get(answer1).to({alpha:0}, 1000);
    }
    if(answer2.label.text == wAnswers[0] || answer2.label.text == wAnswers[1]) {
      createjs.Tween.get(answer2).to({alpha:0}, 1000);
    }
    if(answer3.label.text == wAnswers[0] || answer3.label.text == wAnswers[1]) {
      createjs.Tween.get(answer3).to({alpha:0}, 1000);
    }
    if(answer4.label.text == wAnswers[0] || answer4.label.text == wAnswers[1]) {
      createjs.Tween.get(answer4).to({alpha:0}, 1000);
    }
  });
  joker.alpha = 0;
  
  c_content.addChild(joker);
  createjs.Tween.get(joker).to({alpha:1}, 1000);

  var endGame = createDetailedButton(txt_finish, 0, 450, 50, 50, 12, function(){
    // Stop sound or timeout
    if(currentSound != null) {
      // Remove Sounds
      currentSound.removeEventListener("complete");
      window.clearTimeout(currentSound.timeout);
      currentSound.stop();
      createjs.Sound.removeSound("question");
      createjs.Sound.removeSound("answer1");
      createjs.Sound.removeSound("answer2");
      createjs.Sound.removeSound("answer3");
      createjs.Sound.removeSound("answer4");
    }
    createjs.Sound.play("menuSelect");
    // Show Textfield if he has already scores (Clientside check is enough)
    if(points.text != (txt_points + ": 0")) {
      initPlayernameField();
    } else {
      // Remove Session Variables To Prevent a Continue of the current game
      loadAjaxData("saveScoreEndGame.php");
      initMenuWithSound();
    }
  });
  endGame.alpha = 0;

  c_content.addChild(endGame);
  createjs.Tween.get(endGame).to({alpha:1}, 1000);
  
  // Handle click on an answer
  var clickOnAnswer = function(ev, evtype) {
    // Remove Handlers and add fill graphic
    answer1.removeEventListener("click");
    answer2.removeEventListener("click");
    answer3.removeEventListener("click");
    answer4.removeEventListener("click");
    if(currentSound != null) {
      currentSound.removeEventListener("complete");
      window.clearTimeout(currentSound.timeout);
      currentSound.stop();
      createjs.Sound.removeSound("question");
      createjs.Sound.removeSound("answer1");
      createjs.Sound.removeSound("answer2");
      createjs.Sound.removeSound("answer3");
      createjs.Sound.removeSound("answer4");
    }
    // check answer
    var ansCorrection = loadAjaxDataWithData("loadAnswerCorrection.php", {answer: ev.target.parent.label.text});
    
    // update points
    points.text = txt_points + ": " + ansCorrection[0];
    
    // show stats
    correctAnswered.text = txt_userGotCorrect + ": " + Math.round(ansCorrection[1] / (ansCorrection[1] + ansCorrection[2]) * 100) + "%";
    createjs.Tween.get(correctAnswered).to({alpha:1}, 1000).wait(1000).to({alpha:0}, 1000);
    // move questions out of the screen
    createjs.Tween.get(answer1).to({x:-300}, 1700, createjs.Ease.elasticIn);
    createjs.Tween.get(answer2).to({x:900}, 1700, createjs.Ease.elasticIn);
    createjs.Tween.get(answer3).to({x:-300}, 1700, createjs.Ease.elasticIn);
    createjs.Tween.get(answer4).to({x:900}, 1700, createjs.Ease.elasticIn);
    
    // check if answer correct
    if(ansCorrection[0] > 0) {
      // show message that answer was correct
      question.text = txt_correct;      
      createjs.Sound.play("correct");
      // Clientsite check if there are any questions left (no need for serverside check!)
      if(nOQuestions.max != nOQuestions.cur) {
        // load next question
        window.setTimeout(initNewQuestion, 1700);
      } else {
        window.setTimeout(initPlayernameField, 1700);
      }
    } else {
      question.text = txt_wrong + " - " + txt_rightAnswerWouldBe + " \"" + ansCorrection[3] + "\"";
      // show message that answer was wrong
      createjs.Sound.play("wrong");
      // go back to the menu
      window.setTimeout(initMenuWithSound, 4000);
    }
  }

  // Add a special sound handler on load
  createjs.Sound.removeEventListener("fileload");
  createjs.Sound.addEventListener("fileload", handleQuestionLoad);
  var currentSound = null;
  
  // Special function to play Sound after each other
  function handleQuestionLoad(target) {
    if(target.id == "question") {
      currentSound = createjs.Sound.createInstance("question");
      var answersounds = ["answerA", "answerB", "answerC", "answerD"];
      var i = 0;
      function playNextSound(t, e) {
        if(i < 4) {
          currentSound.timeout = window.setTimeout(function() {
            currentSound = createjs.Sound.play(answersounds[i]);
            currentSound.addEventListener("complete", playNextSound);
            i++;
          }, 1000);
        }
      }
      currentSound.play();
      currentSound.addEventListener("complete", playNextSound);
    }
  }

  // Show New Question
  var initNewQuestion = function() {
    // Load a new Question
    var newQuestion = loadAjaxData("loadRandomQuestion.php");
    
    nOQuestions.text = txt_questions + ": " + newQuestion["answeredQuestions"] + "/" + newQuestion["numberOfQuestions"];
    nOQuestions.max = newQuestion["numberOfQuestions"];
    nOQuestions.cur = newQuestion["answeredQuestions"];
    
    answer1.addEventListener("click", clickOnAnswer);
    answer2.addEventListener("click", clickOnAnswer);
    answer3.addEventListener("click", clickOnAnswer);
    answer4.addEventListener("click", clickOnAnswer);

    // Set text of the Questions, remove alpha values(50/50 Joker) and show them
    question.text = newQuestion['question']['text'];
    createjs.Tween.get(question).to({x:300}, 1700, createjs.Ease.elasticOut);
    answer1.label.text = newQuestion['question']['answers'][0];
    answer1.alpha = 1;
    createjs.Tween.get(answer1).wait(500).to({x:70}, 1700, createjs.Ease.elasticOut);
    answer2.label.text = newQuestion['question']['answers'][1];
    answer2.alpha = 1;
    createjs.Tween.get(answer2).wait(500).to({x:330}, 1700, createjs.Ease.elasticOut);
    answer3.label.text = newQuestion['question']['answers'][2];
    answer3.alpha = 1;
    createjs.Tween.get(answer3).wait(500).to({x:70}, 1700, createjs.Ease.elasticOut);
    answer4.label.text = newQuestion['question']['answers'][3];
    answer4.alpha = 1;
    createjs.Tween.get(answer4).wait(500).to({x:330}, 1700, createjs.Ease.elasticOut);

    // Register the Sounds and play them
    createjs.Sound.registerSound(newQuestion['question']['soundPaths'][0], "question", 1);
    createjs.Sound.registerSound(newQuestion['question']['soundPaths'][1], "answerA", 1);
    createjs.Sound.registerSound(newQuestion['question']['soundPaths'][2], "answerB", 1);
    createjs.Sound.registerSound(newQuestion['question']['soundPaths'][3], "answerC", 1);
    createjs.Sound.registerSound(newQuestion['question']['soundPaths'][4], "answerD", 1);

  };
  initNewQuestion();
    
}

// Show Textbox for Highscore
function initPlayernameField() {
  // Save the endtime
  var newQuestion = loadAjaxData("stopTime.php");
  c_content.removeAllChildren();

  var text = new createjs.Text(txt_playername + ": ", "bold 16px Verdana", "#000000");
  text.x = 150;
  text.y = 154;
  text.alpha = 0;
  c_content.addChild(text);
  createjs.Tween.get(text).to({alpha:1}, 1000);

  var input = createTextbox(150);
  input.x = 300;
  input.y = 150;
  input.alpha = 0;
  c_content.addChild(input);
  createjs.Tween.get(input).to({alpha:1}, 1000);


  var dontsave = createButton(txt_ignore, 150, 250, function(){
    loadAjaxData("saveScoreEndGame.php");
    $("#textbox").remove();
    initMenuWithSound();
    createjs.Sound.play("menuBack");
  });
  dontsave.alpha = 0;
  c_content.addChild(dontsave);
  createjs.Tween.get(dontsave).to({alpha:1}, 1000);


  var save = createButton(txt_saveIntoHighscore, 350, 250, function(){
    if($("#textbox")[0].checkValidity()) {
      loadAjaxDataWithData("saveScoreEndGame.php", {name: $("#textbox").val()});
      $("#textbox").remove();
      createjs.Sound.play("menuSelect");
      initMenuWithSound();
    }
  });
  save.alpha = 0;
  c_content.addChild(save);
  createjs.Tween.get(save).to({alpha:1}, 1000);
}

// Highscore
function initHighscore() {
  c_content.removeAllChildren();

  // GUI STUFF - Init DOM Table and load it into JS
  var table = createTable(
                        "Highscore",
                        460,
                        300,
                        ["ID", txt_rank, txt_categories, txt_playername, txt_calcScore, txt_score, txt_date, txt_duration],
                        [{name:'id',index:'id', width:0, hidden:true},
                         {name:'rank',index:'rank', width:70, sortable: false},
                         {name:'categories',index:'categories', width:300, sortable: false},
                         {name:'playername',index:'playername', width:160, sortable: false},
                         {name:'calcScore',index:'calcScore', width:80, sortable: false},
                         {name:'score',index:'score', width:80, sortable: false},
                         {name:'time',index:'time', width:140, sortable: false},
                         {name:'duration',index:'duration', width:80, sortable: false}],
                        "loadRanking.php");
  table.x = -600;
  table.y = 30;
  c_content.addChild(table);
  createjs.Tween.get(table).to({x:70}, 1700, createjs.Ease.elasticOut);

  var back = createButton(txt_back, -200, 420, function(evt) {
    // Init Back Button
    var soundSelect = createjs.Sound.play("menuBack");
    $("#gbox_toplist").remove();
    initMenu();
  });
  c_content.addChild(back);
  createjs.Tween.get(back).wait(500).to({x:225}, 1700, createjs.Ease.elasticOut);
}

// Utility Functions

// Handle Ajax Request
function loadAjaxData(url) {
  var request = $.ajax({
    url: url,
    async: false,
    dataType: 'json'
  });
  return request.responseJSON;
}

// Handle Ajax Request with Data
function loadAjaxDataWithData(url, data) {
  var request = $.ajax({
    url: url,
    type: "POST",
    data: data,
    async: false,
    dataType: 'json'
  });
  return request.responseJSON;
}

// Create a GUI Button
function createButton(text, x, y, clickHandler) {
  return createDetailedButton(text, x, y, 150, 40, 16, clickHandler);
}

// Create a GUI Button with additional settings
function createDetailedButton(text, x, y, width, height, textHeight, clickHandler) {
  // Init Button Background
  var background = new createjs.Shape();
  background.name = text + "bg";
  background.graphics.beginFill("black").drawRoundRect(0, 0, width, height, 10);

  // Init Button Text
  var label = new createjs.Text(text, "normal " + textHeight + "px Impact", "#ffffff");
  label.name = text + "lbl";
  label.mouseEnabled = false;
  label.textAlign = "center";
  label.textBaseline = "middle";
  label.maxWidth = width;
  label.x = width/2;
  label.y = height/2;

  // Wrap them into a Container
  var button = new createjs.Container();
  button.name = text + "btn";
  button.x = x;
  button.y = y;
  button.label = label;
  button.background = background;
  button.addChild(background, label);

  // Init Handlers
  button.on("mouseover", function(evt) { evt.target.graphics.beginFill("#333333").drawRoundRect(0, 0, width, height, 10);});
  button.on("mouseout", function(evt) { evt.target.graphics.beginFill("black").drawRoundRect(0, 0, width, height, 10);});
  button.on("click", function() { createjs.Tween.removeAllTweens(); clickHandler(); });
  return button;
}

// Create a Selectable DOM List
function createSelectableList(texts, width, height) {
  $("#canvasContainer").prepend("<ol id=\"selectableCategories\" style=\"list-style-type: none; padding: 0; width: " + width + "px; height: " + height + "px; overflow-y: auto;\"></ol>");
  for(entry in texts) {
    $("#selectableCategories").append("<li class=\"ui-widget-content\" style=\"text-align: center; float: left; width: 100px;\">" + texts[entry] + "</li>");
  }
  $("#selectableCategories").selectable();
  return new createjs.DOMElement(document.getElementById("selectableCategories"));
}

// Create a DOM Textbox
function createTextbox(width) {
  $("#canvasContainer").prepend("<input id=\"textbox\" required=\"\" maxlength=\"30\" style=\"width: " + width + "px;\" />");
  return new createjs.DOMElement(document.getElementById("textbox"));
}

// Create a Filterable and Sortable Table
function createTable(title, width, height, columns, columnData, url) {
  $("#canvasContainer").prepend("<table id=\"toplist\"></table><div id=\"ptoplist\"></div>");
  $("#toplist").jqGrid({
     	url:url,
  	  datatype: "json",
     	colNames:columns,
     	colModel:columnData,
     	rowNum:10,
     	rowList:[10,50,100],
     	pager: '#ptoplist',
      viewrecords: true,
      caption:title,
  	height:height,
    width: width
  });
  $("#toplist").jqGrid('navGrid','#ptoplist',{edit:false,add:false,del:false});
  return new createjs.DOMElement(document.getElementById("gbox_toplist"));
}