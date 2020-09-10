<?php
require_once('../includes/_db.config.php');
require_once('../includes/func_check_email.php');
require_once('../includes/func_avatarr_upload.php');

function is_valid_url($url) {
  if(substr($url, 0, 4) != "http")
      return false;
  return true;
}

$error_in_up = 0;
$errors_array = array();
$detail = "";

//Check and make sure password is consistant for both fields
if($_POST['up_pass'] != $_POST['up_pass_confirm']) {
  $error_in_up = 1;
  $errors_array['up_pass'] = 1;
}
elseif($_POST['up_pass'] != "") {
  $pass = md5($_POST['up_pass']);
  $details .= " pass='$pass',";
}

//Also check for correct email syntax
if ($_POST['up_email']) { 
	$email = $_POST['up_email'];
        //check e-mail
        if(check_email($email)) {
          $details .= " email='$email',";
        }
        else {
          $error_in_up = 1;
          $errors_array['up_email'] = 1;
          $errors_array['up_email_value'] = $email;
        }
}
if ($_POST['up_bio']) { 
	$bio = $_POST['up_bio'];
	$details.= " bio='$bio',";
}

//Check if URL was submitted or if image was uploaded
switch($_POST['reg_avatarr_type']) {
  case 'url':    //check if a url was submitted
                 if(isset($_POST['reg_avatarr']) && is_valid_url($_POST['reg_avatarr'])) {
                    avatarr_url($_POST['reg_avatarr']);
                 }
                 break;
  case 'upload': //Get image and resize
                 // check if a file was submitted
                 if(isset($_FILES['userfile'])) {
                     avatarr_upload();
                 }
                 break;
}


if($error_in_up) {
  $errors_output = "";
  $errors_array['bio_up'] = $_POST['up_bio'];
  foreach($errors_array as $value => $key) {
    $errors_output .= "&$value=$key";
  }
  header("Location: ../index.php?page=includes/player_update$errors_output");
}
else {
  //$id = $_SESSION['player_id'];

  //$sql = "UPDATE users SET".$details." WHERE id='".$id."'";
  $details = substr($details, 0, strlen($details)-1);
  $sql = "UPDATE users SET ".$details." WHERE login='".$_SESSION['player_name']."'";
  $result = single_qry($sql);

  header("Location: ../index.php?page=profile");
}
?>
