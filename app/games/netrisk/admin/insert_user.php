<?php
require_once('../includes/func_check_email.php');
require_once('../includes/_db.config.php');

$error_in_reg = 0;
$errors_array = array();

//Check and make sure password is consistant for both fields
//Also check for correct email syntax
if($_POST['reg_pass'] != $_POST['reg_pass_confirm']) {
  $error_in_reg = 1;
  $errors_array['reg_pass'] = 1;
  //$errors_array['reg_pass_confirm'] = 1;
}

if(!check_email($_POST['reg_email'])) {
  $error_in_reg = 1;
  $errors_array['reg_email'] = 1;
}

//Check if username is invalid or taken
$sql = "SELECT login FROM users WHERE login='".$_POST['reg_log']."'";
$result = get_array($sql);

if($result['login'] == $_POST['reg_log']) {
  $error_in_reg = 1;
  $errors_array['reg_log'] = 1;
}

if($error_in_reg) {
  $errors_array['reg_log_value'] = $_POST['reg_log'];
  $errors_array['reg_email_value'] = $_POST['reg_email'];
  $errors_array['reg_bio_value'] = $_POST['reg_bio'];

  $output_header = "Location: ../index.php?page=register";
  foreach($errors_array as $value => $key)
      $output_header .= "&$value=$key";

header($output_header);
}
else {

$login = @strip_tags($_POST['reg_log']);
$login = @stripslashes($login);
$pass = md5($_POST['reg_pass']);
$email = @strip_tags($_POST['reg_email']);
$email = @stripslashes($email);
$bio = @strip_tags($_POST['reg_bio']);
$bio = @stripslashes($bio);


$sql = "INSERT into users(login,pass,email,bio) VALUES(\"$login\",\"$pass\",\"$email\",\"$bio\")";
$result = single_qry($sql);

header("Location: ../index.php?page=gamebrowser");
}
?>
