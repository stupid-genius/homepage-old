<?php
require_once('includes/_db.config.php');

?>
<fieldset><legend>Register a User</legend>
<p>Please enter all the new user info...</p>
<form enctype="multipart/form-data" action="./admin/insert_user.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="120000" />
<input class="loginput" type="text" id="reg_log" name="reg_log" maxlength="16" value="<?php echo $_GET['reg_log_value']; ?>">
<?php 
if($_GET['reg_log'])
  echo ": <b>*Username is taken or invalid</b> - (16 max characters)<br>";
else
  echo ": Username - (16 max characters)<br>";
?>

<input class="loginput" type="password" id="reg_pass" name="reg_pass" maxlength="16">
<?php 
if($_GET['reg_pass'])
  echo " : <b>*Password does not match confirmation</b><br/>";
else
  echo ": Password <br/>";
?>

<input class="loginput" type="password" id="reg_pass" name="reg_pass_confirm" maxlength="16"> : Confirm Password <br/>
<hr>
<input class="loginput" type="text" id="reg_email" name="reg_email" maxlength="60" value="<?php echo $_GET['reg_email_value']; ?>">
<?php 
if($_GET['reg_email'])
  echo " : <b>*: INVALID Email Address!<br>( This <b>must</b> be a legitimate email address to recover a lost password. )</b><br/>";
else
  echo ": Email Address<br>( This <b>must</b> be a legitimate email address to recover a lost password. )<br>";
?>

<hr>
User Bio: (Optional)<br>
<textarea class="newstxt" id="reg_bio"  name="reg_bio" cols="50" rows="5"
id="bio"><?php echo $_GET['reg_bio_value']; ?></textarea><br />
<input class="subnews" type="submit" value="Register"> 
	</form>
</fieldset>

