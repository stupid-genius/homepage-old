<?php
require_once('includes/_db.config.php');

$adminpage = $_GET[adminpage];
    if ($_GET[adminpage]==""){ $adminpage = 'news_post'; }

if ($_SESSION['player_rank'] == 70)
{ 
?><div id="nav">
<a href="index.php?page=admin/index&adminpage=news_post" class="button"> Post to News-Page </a> 
<a href="index.php?page=admin/index&adminpage=memberlist" class="button"> Memberslist </a> 
<a href="index.php?page=admin/index&adminpage=gamelist" class="button">Game List </a> 
</div>
<div class="status_area" style="width:auto;margin: 5px 15px 0 15px;">
<?
 require_once("admin/".$adminpage.".php"); 
}
else {
echo "You are not allowed to access this page.";
}
?></div>


