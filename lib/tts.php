<?php
function getTTSFromGoogle($ttsText) {
  $path = "./sound/" . $_SESSION['language'] . "/tts/" . md5($ttsText) . ".mp3";
  if(!file_exists($path)) {
    $baseurl = "http://translate.google.com/translate_tts?";
    $params = http_build_query(array(
      'tl' => $_SESSION['language'],
      'ie' => 'UTF-8',
      'q' => $ttsText
    ));
    $sound = file_get_contents($baseurl.$params);
    //echo "http://translate.google.com/translate_tts?ie=UTF8&tl=" . $_SESSION['language'] . "&q=" . urlencode($ttsText) . "&total=1&idx=0&textlen=" . strlen(urlencode($ttsText)) . "&prev=input";
    file_put_contents($path, $sound);
  }
  return $path;
}
 ?>