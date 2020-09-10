<?php
    require_once('includes/_db.config.php');
    
    if($_GET['debug'])
       require_once('includes/debug_funcs.php');
    
    
    /*MW: moving this up here allowed page title to be set right*/
    $current_page=$_GET[page];
    if ($_GET[page]==""){
    	$current_page="gamebrowser";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="index,follow" />
        <meta name="KEYWORDS" content="netrisk, phprisk, risk, online" />
        <meta name="DESCRIPTION" content="Play the game Risk online with a browser." />
        <title>netRisk - <?echo $current_page ?></title> 
        <!--MW: uncomment and edit this to add a shortcut icon thingy -->
        <!--<link rel="SHORTCUT ICON" href="favicon.ico" />-->
        <link rel="stylesheet" type="text/css" media="screen" title="default" href="css/base.css" />
        <!--link rel="stylesheet" type="text/css" media="screen" title="default" href="css/base.css" / -->
        <!--MW: uncomment and edit this to add alternate stylesheets (themes) -->
        <!--<link rel="alternate stylesheet" type="text/css" media="screen" title="text" href="text.css" />-->
        <script type="text/javascript">
            function OffError(){
            	document.getElementById('error').style.display = 'none';
            }
                   
            <?php if($_GET['debug']) { ?>
                function exitpop(myThing)
                {
                    my_window= window.open ("",
                        "mywindow1","status=1,width=350,height=150,scrollbars=1,resizable=1");
                   my_window.document.write('<table border=1 cellspacing=0>');
                   my_window.document.write('<tr><th>Key</th><th>Value</th></tr>');
                   for (i in myThing) {
                       my_window.document.write('<tr><td>' + i + '</td><td>' + myThing[i] + '</td></tr>');
                   }
                }
            <?php } ?>
    
        </script>
        <?php if($_GET['debug']) {?>
            <script type="text/javascript" language="javascript" src="includes/PHP_Unserialize.js"></script> 
        <?php  } ?>
    </head>
    <body>
        <div id="header" >
            <div id="logo">
                <img src="images/logo.gif" alt="Welcome to Netrisk!" />
                <span id="version"><?= $version ?></span>
            </div>
            <?php require_once("includes/form_login.php"); ?>
        </div>
        <?php require_once("includes/navigation.php"); ?>
	<div id="index_container">

            <div id="index_content">
                <div class="column-in">    
                    <?php require_once("".$current_page.".php"); ?>
                </div>
            </div>

	    <div id="index_image" style="position: absolute; left: 0px; padding: 0px; margin: 0px;"><p>&nbsp;</p></div>

	    <div id="index_info">
                <div class="column-in">
                    <?php  require_once('includes/topten.php'); ?>
                    <br>
                    <?php require_once('includes/mygames.php'); ?>
                </div>
            </div>
        </div>
        
        <div id="footer" style="background-color: #192614;">
            <div style="position: absolute; left: 10px;background-color: #192614;">
            Copyright, GNU <a href="LICENSE">License</a>
            </div>
            <div style="position: absolute; right: 10px;background-color: #192614;">
            <?php //users online
		include("online.php");
            ?>
            </div>
            
	    <?php //debug code
            if($_GET['debug']) {
        
               $_DEBUGNETRISK['SESSION'] = multi_array_to_string($_SESSION);
               $_DEBUGNETRISK['GET'] = multi_array_to_string($_GET);
               $_DEBUGNETRISK['POST'] = multi_array_to_string($_POST);
        
        
               $var = array();
               foreach ($_DEBUGNETRISK as $k => $v) {
                       $var[$k] = $v;
               }
        
               $serialized = EscapeString(serialize($var));
        
            ?>
            <script language="javascript" type="text/javascript">
            <!--
                toUnserialize = '<?=$serialized?>';
        
                myThing = PHP_Unserialize(toUnserialize);
            // -->
            </script> 
            <a onClick="exitpop(myThing);">Debug Output</a>
            <?php
                }
            ?>
        </div>
        
    </body>
</html>

