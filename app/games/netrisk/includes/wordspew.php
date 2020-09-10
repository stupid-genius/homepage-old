<?php
require_once('_db.config.php');
/*
Plugin Name: Jalenack's Wordspew
Plugin URI: http://blog.jalenack.com/ajax/
Description: A plugin that creates a live shoutbox, using AJAX as a backend. Users can chat freely from your blog without refreshing the page! It uses the Fade Anything Technique for extra glamour
Author: Andrew Sutherland
Version: 1.16
Author URI: http://blog.jalenack.com
*/

// Version of this plugin. Not very useful for you, but for the dev
$jal_version = "1.16";

// The required user level needed to access the admin page for this plugin
$jal_admin_user_level = 8;

// The number of comments that should show up in one viewing.
$jal_number_of_comments = 35;

/*
==================// Important Info //=======================================

This file is called in three different places. First, it is called by Wordpress
so that it can display the jal_get_shoutbox function.

Secondly, it is called by javascript. Because it is called by javascript and not by wordpress,
normal plugin procedure won't work without directly calling the wp-config file in your root
directory. We cannot use the $wpdb object. So instead, we open up the config file, comment out
the bit about calling the rest of Wordpress, and then evaluate the config file to extract how to 
access the database. We do all this so that the entirety of Wordpress is not loaded by each call
to the server, which would put great unneccessary load on the server and conflict with other plugins.

To differentiate between the two remote calls, I've used the $jalGetChat and $jalSendChat variables.
These two variables are sent by the javascript file as "yes". That way, the script will only perform
certain actions if it is called by the javascript file.

Thirdly, it is called by the wordpress admin panel, which allows users to edit settings for this plugin

==================// End of Info //==========================================
*/

// Register globals - Thanks Karan et Etienne
$jal_lastID    = isset($_GET['jal_lastID']) ? $_GET['jal_lastID'] : "";
$jal_user_name = isset($_POST['n']) ? $_POST['n'] : ""; 
$jal_user_url  = isset($_POST['u']) ? $_POST['u'] : "";
$jal_user_text = isset($_POST['c']) ? $_POST['c'] : "";
$jalGetChat    = isset($_GET['jalGetChat']) ? $_GET['jalGetChat'] : "";
$jalSendChat   = isset($_GET['jalSendChat']) ? $_GET['jalSendChat'] : "";
// function to print the external javascript and css links
function jal_add_to_head () {
    global $jal_version;
  
      $jal_wp_url = (dirname($_SERVER['PHP_SELF']) == "/") ? "/" : dirname($_SERVER['PHP_SELF']) . "/";
      //$jal_wp_url = get_bloginfo('wpurl') . "/";
    
    echo '
    <!-- Added By Wordspew Plugin. Version '.$jal_version.' -->
    <link rel="stylesheet" href="'.$jal_wp_url.'includes/css.php" type="text/css" />
    <script type="text/javascript" src="'.$jal_wp_url.'includes/fatAjax.php"></script>
		';
}


// In the administration page, add some style...
function jal_add_to_admin_head () {
?>
<style type="text/css">

/* Styles added by Wordspew shoutbox plugin */

input[name=jal_delete]:hover, #jal_truncate_all:hover {
background: #c22;
color: #fff;
cursor: pointer;
}

input[name=jal_edit]:hover {
background: #2c2;
color: #fff;
cursor: pointer;
}

#shoutbox_options p {
text-indent: 15px;
padding: 5px 0;
color: #555;
}

#shoutbox_options span {
border: 1px dotted #ccc;
padding: 4px 14px;
}
</style>

<?php 
}


// Time Since function courtesy 
// http://blog.natbat.co.uk/archive/2003/Jun/14/jal_time_since

// Works out the time since the entry post, takes a an argument in unix time (seconds)
function jal_time_since($original) {
    // array of time period chunks
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
    );
    $original = $original - 10; // Shaves a second, eliminates a bug where $time and $original match.
    $today = time(); /* Current unix time  */
    $since = $today - $original;
    
    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        
        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }
    
    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    
    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];
        
        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
    }
    return $print;
}

////////////////////////////////////////////////////////////
// Functions Below are for getting comments from the database
////////////////////////////////////////////////////////////

// Never cache this page
if ($jalGetChat == "yes" || $jalSendChat == "yes") {
	header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
	header( "Last-Modified: ".gmdate( "D, d M Y H:i:s" )."GMT" ); 
	header( "Cache-Control: no-cache, must-revalidate" ); 
	header( "Pragma: no-cache" );
	header("Content-Type: text/html; charset=utf-8");
	
	//if the request does not provide the id of the last know message the id is set to 0
	if (!$jal_lastID) $jal_lastID = 0;
}

// retrieves all messages with an id greater than $jal_lastID
//echo "JalGetChat is: " . $jalGetChat;

if ($jalGetChat == "yes") {
	jal_getData($jal_lastID);
}

// Where the shoutbox receives information
function jal_getData ($jal_lastID) {

        include('riskconfig.php');
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname, $conn);

        $id = $_SESSION['game_id'];
        //$id = 142;
	$sql = "SELECT * FROM ".$table_prefix."gamechat_".$id." WHERE id > ".$jal_lastID." ORDER BY id DESC";

	$results = mysql_query($sql, $conn);
	$loop = "";

	while ($row = mysql_fetch_array($results)) {

		$id   = $row[0];
		$time = $row[1];
		$name = $row[2];
		$text = $row[3];
		$url  = $row[4];
		
		// append the new id's to the beginning of $loop
		$loop = $id."---".stripslashes($name)."---".stripslashes($text)."---".jal_time_since($time)." ago---".stripslashes($url)."---" . $loop; // --- is being used to separate the fields in the output
	}
	echo $loop;
	
	// if there's no new data, send one byte. Fixes a bug where safari gives up w/ no data
	if (empty($loop)) { echo "0"; }
}

function jal_special_chars ($s) {
  $s = htmlspecialchars($s, ENT_COMPAT,'UTF-8');
  return str_replace("---","&minus;-&minus;",$s);
}

////////////////////////////////////////////////////////////
// Functions Below are for submitting comments to the database
////////////////////////////////////////////////////////////

// When user submits and javascript fails
if (isset($_POST['shout_no_js'])) {
	if ($_POST['shoutboxname'] != '' && $_POST['chatbarText'] != '') {
		jal_addData($_POST['shoutboxname'], $_POST['chatbarText'], $_POST['shoutboxurl']);
		
		jal_deleteOld(); //some database maintenance
    	
    	setcookie("jalUserName",$_POST['shoutboxname'],time()+60*60*24*30*3,'/');
    	setcookie("jalUrl",$_POST['shoutboxurl'],time()+60*60*24*30*3,'/');
        //take them right back where they left off
		header('location: '.$_SERVER['HTTP_REFERER']);
	} else echo "You must have a name and a comment";
}

	//only if a name and a message have been provides the information is added to the db
if ($jal_user_name != '' && $jal_user_text != '' && $jalSendChat == "yes") {
		jal_addData($jal_user_name,$jal_user_text,$jal_user_url); //adds new data to the database
		jal_deleteOld(); //some database maintenance
		echo "0";
}

function jal_addData($jal_user_name,$jal_user_text,$jal_user_url) {
	//the message is cut of after 500 letters
	$jal_user_text = substr($jal_user_text,0,500); 
	
	$jal_user_name = substr(trim($jal_user_name), 0,18);

///// The code below can mess up multibyte strings

// If there isn't a url, truncate the words to 25 chars each
//	if (!preg_match("`(http|ftp)+(s)?:(//)((\w|\.|\-|_)+)(/)?(\S+)?`i", $jal_user_text, $matches))
//		$jal_user_text = preg_replace("/([^\s]{25})/","$1 ",$jal_user_text);


	// CENSORS .. default is off. To turn it on, uncomment the line below. Add new lines with new censors as needed.	
	//$jal_user_text = str_replace("fuck", "****", $jal_user_text);

	$jal_user_text = jal_special_chars(trim($jal_user_text));
	$jal_user_name = (empty($jal_user_name)) ? "Anonymous" : jal_special_chars($jal_user_name);
	$jal_user_url = ($jal_user_url == "http://") ? "" : jal_special_chars($jal_user_url);

	$html = implode('', file("riskconfig.php"));
	$html = str_replace ("require_once", "// ", $html);
	$html = str_replace ("<?php", "", $html);
	eval($html);
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname, $conn);

        $id = $_SESSION['game_id'];
	mysql_query("INSERT INTO ".$table_prefix."gamechat_".$id." (time,name,text,url) VALUES ('".time()."','".mysql_real_escape_string($jal_user_name)."','".mysql_real_escape_string($jal_user_text)."','".mysql_real_escape_string($jal_user_url)."')", $conn);
}

// Prints the html structure for the shoutbox
	function jal_get_shoutbox () {
        require_once('this-config.php');
	global $wpdb, $table_prefix, $jal_number_of_comments;
?>
            <div id="wordspew">
				<div id="chatoutput">
					<?php
								 								
                                                                $id = $_SESSION['game_id']; 
								$results = get_results("SELECT * FROM ".$table_prefix."gamechat_".$id." ORDER BY id DESC LIMIT ".$jal_number_of_comments);
								
								// Will only add the last message div if it is looping for the first time
								$jal_first_time = true; 
								

								// Loops the messages into a list
								if($results) {
                                                                  foreach($results as $r ) { 
								     // Add links								
								     $r['text'] = preg_replace( "`(http|ftp)+(s)?:(//)((\w|\.|\-|_)+)(/)?(\S+)?`i", "<a href=\"\\0\">&laquo;link&raquo;</a>", $r['text']);

								if ($jal_first_time == true) { echo '<div id="lastMessage"><span>Latest Message</span> <em id="responseTime">'.jal_time_since( $r['time'] ).' ago</em></div>
 						<ul id="outputList">
 						'; }
 						
 						if ($jal_first_time == true) $lastID = $r['id'];
 						
 						$url = (empty($r['url']) && $r['url'] = "http://") ? $r['name'] : '<a href="'.$r['url'].'">'.$r['name'].'</a>';

 						 	foreach($_SESSION['PLAYERS'] as $aplayer) {
                                                          if($aplayer['name'] == stripslashes($url))
                                                                $bgcolor = $aplayer['color'];
                                                        }					

						        echo "<li><span title=\"".jal_time_since( $r['time'] )."\" style=\"background-color: ".$bgcolor."; font-weight: bold;\">".stripslashes($url)."</span>: ".convert_smilies(" ".stripslashes($r['text']))."</li>"; 

						        
					$jal_first_time = false; } 
					
					// If there is less than one entry in the box
					} else {
					echo "No messages in chat yet. <b>Be the first!</b>";
					}
					
					$use_url = (get_option('shoutbox_use_url') == "true") ? TRUE : FALSE;
					$use_textarea = (get_option('shoutbox_use_textarea') == "true") ? TRUE : FALSE;
					$registered_only = (get_option('shoutbox_registered_only') == "1") ? TRUE : FALSE;
					
					global $user_level, $user_nickname, $user_url, $user_ID, $jal_admin_user_level;
					get_currentuserinfo(); // Gets logged in user.

				?>
</ul>

				</div>
		<?php if (!$registered_only || ($registered_only && $user_ID)) { ?>
				<form id="chatForm" method="post" action="includes/wordspew.php">
				    <p><?php
					$user_nickname = $_SESSION['player_name'];
					if (!empty($user_nickname)) { /* If they are logged in, then print their nickname */ ?>
						
					<input type="hidden" name="shoutboxname" id="shoutboxname" value="<?php echo $user_nickname; ?>" />
					<input type="hidden" name="shoutboxurl" id="shoutboxurl" value="<?php if($use_url) { echo $user_url; } ?>" />
					<?php } else { echo "\n"; /* Otherwise allow the user to pick their own name */ ?>
					<label for="shoutboxname">Name:</label>
					<input type="text" name="shoutboxname" id="shoutboxname" value="<?php if ($_COOKIE['jalUserName']) { echo $_COOKIE['jalUserName']; } ?>" />
					<?php if (!$use_url) { echo '<span style="display: none">'; } ?>
					<label for="shoutboxurl">URL:</label>
					<input type="text" name="shoutboxurl" id="shoutboxurl" value="<?php if ($_COOKIE['jalUrl']) { echo $_COOKIE['jalUrl']; } else { echo 'http://'; } ?>" />
					<?php if (!$use_url) { echo "</span>"; } ?>
					<?php  } echo "\n"; ?>
					<?php if ($use_textarea) { ?>
					<textarea rows="2" cols="14" name="chatbarText" id="chatbarText" onkeypress="return pressedEnter(this,event);"></textarea>
					<?php } else { ?>
					<input type="text" name="chatbarText" id="chatbarText" />
					<?php } ?>
					<input type="hidden" id="jal_lastID" value="<?php echo $lastID + 1; ?>" name="jal_lastID" />
					<input type="hidden" name="shout_no_js" value="true" />
					<input type="submit" id="submitchat" name="submit" value="Send" />
					</p>
				</form>
		<?php } else echo "<p>You must be a registered user to participate in this chat</p>"; ?>
            </div>
<?php }

 ?>
