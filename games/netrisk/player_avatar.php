<?php
require_once('includes/riskconfig.php');

    // just so we know it is broken
    error_reporting(E_ALL);
    // some basic sanity checks
    if(isset($_GET['image_id']) && is_numeric($_GET['image_id'])) {
        //connect to the db
        $link = mysql_connect($dbhost, $dbuser, $dbpass) or die("Could not connect: " . mysql_error());

        // select our database
        mysql_select_db($dbname) or die(mysql_error());

        // get the image from the db
        $sql = "SELECT avatar, image_type FROM users WHERE id='".$_GET['image_id']."'";

        // the result of the query
        $result = mysql_query("$sql") or die("Invalid query: " . mysql_error());

        // set the header for the image
        $row = mysql_fetch_array($result);


          header("Content-type: ".$row['image_type']);
          //echo mysql_result($result, 0);
          echo $row['avatar'];



        // close the db link
        mysql_close($link);
    }
    else {
        echo 'Please use a real id number';
    }
?>
