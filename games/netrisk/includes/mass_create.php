<?php
require_once('_db.config.php'); // loads Pear DB and database connection string
require_once('functions.php');

if(isset($_GET['chatcreate'])) {

$_SESSION['chatid'] = null;
$_SESSION['chattitle'] = null;
$_SESSION['chatadmin'] = null;
// GET GAME SETTINGS
    $chattitle = @strip_tags($_GET['title']);
    $playername = $_SESSION['player_name'];
    $now = date("m/d H:i");
    
    // CREATE THE GAME TABLE
    $sql = "INSERT INTO chat (title, admin, date) VALUES (\"$chattitle\", \"$playername\", \"$now\")";
    $q = single_qry($sql);
    
    
    header("Location: ../index.php?page=includes/mass_browser");
    exit;
} else { ?>
    <form action="includes/mass_create.php" >
    chat title: <input type="text" name="title" /><br/>
    <input type="hidden" value="1" name="chatcreate" />
    <input type="submit" value="create" class="button" />
    </form>
<?php
}
?>
