<?php
require_once("riskconfig.php");

function i18n($text){
  global $language;
  global $i18n;
  return $i18n[$language][$text];
}

?>