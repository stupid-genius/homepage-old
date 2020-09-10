<script type="text/javascript">
<!--

function showhide(layer_show, layer_hide)
{
  if (document.all) { //IS IE 4 or 5 (or 6 beta)
    eval( "document.all." + layer_show + ".style.visibility = 'visible'");
    eval( "document.all." + layer_hide + ".style.visibility = 'hidden'");
  }
  if (document.layers) { //IS NETSCAPE 4 or below
    document.layers[layer_show].visibility = 'visible';
    document.layers[layer_hide].visibility = 'hidden';
  }
  if (document.getElementById && !document.all) {
    mylayer_show = document.getElementById(layer_show);
    mylayer_hide = document.getElementById(layer_hide);
    mylayer_show.style.visibility = 'visible';
    mylayer_hide.style.visibility = 'hidden';
  }
}

//-->
</script>


<?php
require_once('includes/_db.config.php');

$sql = "SELECT * FROM users WHERE login='".$_SESSION['player_name']."'";
$userinfo = get_assoc($sql);

if(substr($userinfo['image_name'],0 ,4) == "http")
  $_GET['up_reg'] = 1;

?><div id="content">
<fieldset><legend>Edit your info...</legend>
<form action="./admin/update_user.php" method="post" name="up_form" enctype="multipart/form-data">
<strong><?php echo $_SESSION['player_name']; ?> (<?php echo $userinfo['id'];?>)</strong> : Username<br>
<input class="loginput" type="password" id="up_pass" name="up_pass" maxlength="16"> : <?php
if(isset($_GET['up_pass']))
  echo "<b>Password is not confirmed</b> (Leave blank to keep current password)<br>";
else
  echo "Password (Leave blank to keep current password)<br>";
?>
<input class="loginput" type="password" id="up_pass" name="up_pass_confirm" maxlength="16"> : Confirm Password<br>
<input class="loginput" type="text" id="up_email" name="up_email" value="<?php 
if(isset($_GET['up_email_value']))
  echo $_GET['up_email_value'];
else
  echo $userinfo['email'];?>" maxlength="60"> : <?php 
if(isset($_GET['up_email_value']))
  echo "<b>Invalid Email Address</b>";
else
  echo "Email Address";?><br>
<hr>
<div style="width: 100%;">
<?php
if($userinfo['image_type'] != "") {?>
<div style="float: right; border: solid 1px #ffffff;">
<center>
<img src="player_avatar.php?image_id=<?php echo $userinfo['id'];?>"><br>
Current Image
</center>
</div>
<?php 
}

if($_GET['err_avatarr'])
  echo " : <b>*: INVALID Avatarr image or URL</b><br/>";
else
  echo "Avatarr image (Optional)<br>(Click browse to upload image or type in URL location. 120 kB max. Image will be resized to 40x40.)<br>";
?>
<INPUT type="radio" name="reg_avatarr_type" value="url" onfocus="showhide('avatarr_url', 'avatarr_upload');return true;" <?php if(isset($_GET['up_reg']))
  echo "checked";?>>Specify URL | <INPUT type="radio" onfocus="showhide('avatarr_upload', 'avatarr_url');return true;" name="reg_avatarr_type" value="upload" <?php
if(!isset($_GET['up_reg']))
  echo "checked";?>>Upload Image
<div style="position: relative;">
<div style="position: absolute;" style="top: 0px; left: 0px;" id="avatarr_url"><input class="loginput" type="text" id="reg_avatarr" name="reg_avatarr" maxlength="80" value="<?php echo $userinfo['image_name']; ?>"></div>
<div style="position: absolute;" style="top: 0px; left: 0px;" id="avatarr_upload"><input class="loginput" name="userfile" type="file" /></div>
<br><br>
</div>
</div>
<input type="hidden" name="MAX_FILE_SIZE" value="150000" />
<hr>
User Bio (Optional):<br>
<textarea class="newstxt" id="up_bio"  name="up_bio" cols="50" rows="5"
id="bio"><?php
if(isset($_GET['bio_up']))
  echo $_GET['bio_up'];
else
  echo $userinfo['bio'];?></textarea><br />
<input class="subnews" type="submit" value="Update Info"> 
	</form>
</fieldset>
 <SCRIPT language="JavaScript">
document.up_form.reg_avatarr_type[<?php
if(isset($_GET['up_reg']))
   echo "0";
else
   echo "1";
?>].focus();
document.up_form.up_bio.focus();
</SCRIPT>
</div>
