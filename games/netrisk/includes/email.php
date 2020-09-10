<?php
// First, define who, what, went and where.
$to = "daggerhart@gmail.com";
$subject = "Game update";
$body = "It is now your turn in the netrisk game.";
$header = 'From: NetRisk <webmaster@phprisk.org>'; // otherwise, it will send it as the servers username

if (mail($emailad, $subject, $body, $header)) {
  printf("<p>Message successfully sent!</p>");
 } else {
  printf("<p>Message delivery failed... $emailad</p>");
 }
?>
