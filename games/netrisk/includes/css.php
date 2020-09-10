<?php
	
	/*
	This file is php so that admins can control its look without editing the code.
	The headers below tell the browser to cache the file and also tell the browser it is css.
	*/
	
	header("Cache-Control: must-revalidate");
	$offset = 60*60*24*60;
	$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s",time() + $offset)." GMT";
	header($ExpStr);
	header('Content-Type: text/css');
	
	include("this-config.php");
?>

/*
This file controls the look of the Live shoutbox...
*/


#chatoutput {

/* Height of the shoutbox*/
height: 200px;

/*Uncomment width below*/
/*width: 220px;*/

/* Horizontal Scrollbar Killer */
padding: 6px 8px; 

/* Borders */
border: 1px solid #<?php echo get_option('shoutbox_name_color'); ?>;
border-width: 1px 2px;

font: 11px helvetica, arial, sans-serif;
color: #<?php echo get_option('shoutbox_text_color'); ?>;
background: #<?php echo get_option('shoutbox_fade_to'); ?>;
overflow: auto;
margin-top: 10px;
}

#chatoutput span {
font-size: 1.1em;
color: #<?php echo get_option('shoutbox_name_color'); ?>;
}

#chatForm label, #shoutboxAdmin {
display: block;
margin: 4px 0;
}

#chatoutput a {
font-style: normal;
font-weight: bold;
color: #<?php echo get_option('shoutbox_name_color'); ?>
}

/* User names with links */
#chatoutput li span a {
font-weight: normal;
display: inline !important;
border-bottom: 1px dotted #<?php echo get_option('shoutbox_name_color'); ?>
}

#chatForm input, #chatForm textarea {
width: 110px;
display: block;
margin: 0 auto;
}

#chatForm textarea {
width: 110px;
}


#chatForm input#submitchat {
width: 70px;
margin: 10px auto;
border: 2px outset;
padding: 2px;
}

#chatoutput ul#outputList {
padding: 0;
position: static;
margin: 0;
}

#chatoutput ul#outputList li {
padding: 4px;
margin: 0;
color: #<?php echo get_option('shoutbox_text_color'); ?>;
background: none;
font-size: 1em;
list-style: none;
}

/* No bullets from Kubrick et al. */
#chatoutput ul#outputList li:before {
content: '';
}

ul#outputList li:first-line {
line-height: 16px;
}

#lastMessage {
padding-bottom: 2px;
text-align: center;
border-bottom: 2px dotted #<?php echo get_option('shoutbox_fade_from'); ?>;
}

em#responseTime {
font-style: normal;
display: block;
}

#chatoutput .wp-smiley {
vertical-align: middle;
}