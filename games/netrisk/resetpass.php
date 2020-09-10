<?php
if(!isset($_POST['submit']) && !isset($_GET['confirmpass'])) {
?>
<br>
<form method="POST" action="<?php echo $_SERVER[PHP_SELF];?>?page=resetpass">
Username: <input type="text" name="usernamereq" length=30><br>
<input type="SUBMIT" name="submit" value="Request Reset">
</form>
<?php
}
elseif(isset($_POST['submit']) && !isset($_GET['confirmpass'])){
//Query Database for username or email address
$query = "SELECT * FROM users WHERE login='$usernamereq'";
$result = get_array($query);

/*
echo "<pre>";
print_r($result);
echo "</pre>";
*/

//If found, say that a confirmation e-mail will be sent
if($result)
  echo "An email will be sent to: " . $result['email'];
else
  die("That username was not found");

$contents = "Please click or paste the link below into your browser:\n".
            "http://" . $_SERVER["SERVER_NAME"] . $_SERVER[PHP_SELF] . 
            "?page=resetpass&username=".$result['login'].
            "&confirmpass=".$result['pass'];

mail($result['email'], "Password Reset", $contents, "From: ".$adminemail);

}
elseif(!isset($_POST['submit']) && isset($_GET['confirmpass'])){


  if(!isset($_GET['username'])) {
    echo "Invalid password reset request";
  }
  else {
    //Ask for new password for username
?>
<br>
<form method="POST" action="<?php echo $_SERVER[PHP_SELF];?>?page=resetpass&<?php echo "username=".$_GET['username']."&confirmpass=".$_GET['confirmpass'];?>">
New Password: <input type="password" name="userpass1" length=30><br>
Confirm Password: <input type="password" name="userpass2" length=30><br>
<input type="SUBMIT" name="submit" value="Request Reset">
</form>
<?php
  }

}
elseif(isset($_POST['submit']) && isset($_GET['confirmpass'])){
  if(!isset($_GET['username'])) {
    echo "Invalid password reset request";
  }
  else {
    //Go ahead and validate the confirmation pass again to the user
    $query = "SELECT * FROM users WHERE login='".$_GET['username']."'";
    $result = get_array($query);

/*
    echo "<pre>";
    print_r($result);
    echo "</pre>";
*/

    //If it checks out, go ahead and apply the change
    if($result['pass'] == $_GET['confirmpass'])
        if($_POST['userpass1'] != $_POST['userpass1']) {
?>
<br>
The password did not match in both fields.  Please, try again.
<form method="POST" action="<?php echo $_SERVER[PHP_SELF];?>?page=resetpass&<?php echo "username=".$_GET['username']."&confirmpass=".$_GET['confirmpass'];?>">
New Password: <input type="password" name="userpass1" length=30><br>
Confirm Password: <input type="password" name="userpass2" length=30><br>
<input type="SUBMIT" name="submit" value="Request Reset">
</form>
<?php
        }
        else {
           $query = "UPDATE users SET pass='".md5($_POST['userpass1'])."' WHERE login='".$_GET['username']."'";
           single_qry($query);
           echo "Password updated";
        }
    //Else someone is up to some funny business
    else {
      echo "Invalid password reset request.";
    }
  }
}

?>