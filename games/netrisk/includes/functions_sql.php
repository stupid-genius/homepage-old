<?php

// pass all errors to log here
function crit_error($error) { }

function report_error($what, $string, $customError="none") {
	die('<b>* Failed to '.$what.
		'<br /> query</b>: '. $string . 
		'<br/> <b>page</b>: ' . $_SERVER['PHP_SELF'] . 
		'<br/><b>error</b>: '.mysql_errno().':' .mysql_error().
		'<br/><b>custom Error</b>: '.$customError);
}

//die("<b>A fatal MySQL error occured</b>.\n<br />Query: " . $query . "<br />\nError: (" . mysql_errno() . ") " . mysql_error());

// for 'UPDATE' and 'INSERT'
function single_qry($string, $customError="none") {
	$result = mysql_query($string) or report_error("single_qry", $string, $customError);
	return $result;	
}

//retrieves 1 piece of data
function get_one($string, $customError="none") {
	$q = mysql_query($string) or report_error("get_one", $string, $customError);
	while ($row = mysql_fetch_array($q)) { $result = $row[0]; }
	return $result;
}
// retrieves array of data 
function get_array($string, $customError="none") {
	$q = mysql_query($string) or report_error("get_array",$string, $customError);
	$result = mysql_fetch_array($q);
	return $result;
}
// retrieves associative array
function get_assoc($string, $customError="none") {
	$q = mysql_query($string) or report_error("get_assoc", $string, $customError);
	$result = mysql_fetch_assoc($q);
	return $result;
}


function get_col($string, $customError="none") {

  $q = mysql_query($string) or die($customError."<br/>- ".mysql_error());  

  while($row = mysql_fetch_array($q)) {

      $result[] = $row; }

  return $result;


}
?>
