<? 
//Database 
require_once('includes/_db.config.php');

//Customizations 
$timeoutseconds 	= "300";			// How long it it boefore the user is no longer online
$showlink         = "0";                  // Link to us? 1 = Yes  0 = No

//Only one person is online
$oneperson1       = "There is currently";  //Change the text that will be displayed
$oneperson2       = "player online.";     //Change the text that will be displayed

//Two or more people online
$twopeople1       ="There are currently"; //Change the text that will be displayed
$twopeople2       ="players online.";      //Change the text that will be displayed

if ($_SESSION['player_name']) { $user = $_SESSION['player_name']; } else { $user = "visitor"; }

//The following should only be modified if you know what you are doing
$timestamp=time();                                                                                            
$timeout=$timestamp-$timeoutseconds;  

@mysql_db_query($dbname, "INSERT INTO online VALUES ('$timestamp','$REMOTE_ADDR','$_SERVER[PHP_SELF]', '$user')") or die("online Database INSERT Error"); 
@mysql_db_query($dbname, "DELETE FROM online WHERE timestamp<$timeout") or die("online Database DELETE Error");
$result=@mysql_db_query($dbname, "SELECT DISTINCT ip FROM online WHERE file='$_SERVER[PHP_SELF]'") or die("online Database SELECT Error");
$user  =@mysql_num_rows($result);                                                                              
                                                                                               
if ($user==1) {echo"<span style='font-size: .6em; margin-left: 50 px;'>$oneperson1 $user $oneperson2</span>";} else {echo"<span style='font-size: .6em; margin-left: 50 px;'>$twopeople1 $user $twopeople2</span>";}

?>
