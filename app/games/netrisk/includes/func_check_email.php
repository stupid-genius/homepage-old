<?php

if (!function_exists ('getmxrr') ) {
function getmxrr($hostname, &$mxhosts, &$mxweight) {
if (!is_array ($mxhosts) ) {
$mxhosts = array ();
}

if (!empty ($hostname) ) {
$output = "";
@exec ("nslookup.exe -type=MX $hostname.", $output);
$imx=-1;

foreach ($output as $line) {
$imx++;
$parts = "";
if (preg_match ("/^$hostname\tMX preference = ([0-9]+), mail exchanger = (.*)$/", $line, $parts) ) {
$mxweight[$imx] = $parts[1];
$mxhosts[$imx] = $parts[2];
}
}
return ($imx!=-1);
}
return false;
}
}

function check_email($email)
{

   // Create the syntactical validation regular expression
   $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";

   // Presume that the email is invalid
   $valid = 0;

   // Validate the syntax
   if (eregi($regexp, $email))
   {
      list($username,$domaintld) = split("@",$email);
      // Validate the domain
     //   if (getmxrr($domaintld,$mxrecords)) {
         $valid = 1;
   } else {
      $valid = 0;
   } 
   
   return $valid;

}

?>