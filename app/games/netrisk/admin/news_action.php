<?php
// include the database config
require_once('../includes/_db.config.php');
require_once('../includes/functions.php');

// check if the form is submitted
if(isset($_POST['btnSign']))
{
	// get the input from $_POST variable
	// trim all input to remove extra spaces
	$name    = $_POST['txtName'];
	$title   = $_POST['txtTitle'];
	$icon     = $_POST['txtIcon'];
	$message = $_POST['mtxMessage'];
	
	// escape the message ( if it's not already escaped )
	if(!get_magic_quotes_gpc())
	{
		$name    = addslashes($title);
		$message = addslashes($message);
	}
	

	// prepare the query string
	$query = "INSERT INTO news (name, title, message, entry_date) " .
	         "VALUES ('$name', '$title', '$message', current_date)";

	// execute the query to insert the input to database
	// if query fail the script will terminate		 
	mysql_query($query) or die('Error, query failed. ' . mysql_error());
	
	// redirect to current page so if we click the refresh button 
	// the form won't be resubmitted ( as that would make duplicate entries )
	header('Location: ' . $_SERVER['REQUEST_URI']);
	
	// force to quite the script. if we don't call exit the script may
	// continue before the page is redirected
header('Location:  ../index.php?page=news');
	exit;
} 
?>