<div id="login" style="text-align:right;">
<?php 
if (!isset($_SESSION['player_name']))
{ ?>
<form action="includes/user_login.php" method="post"><div><!--SOMETHING is adding a 
validation-failing hidden input here. is it in user_login.php? the PEAR include file?-->
		<!-- legend>Login to Phprisk</legend -->
		<label for="username">Username:</label>	<input class="button" id="username" name="login" type="text" maxlength="32" size="9" />
		<label for="pass">Password:</label>	<input class="button" id="pass" name="pass" type="password" size="9" />
		<input id="id" type="hidden" name="id" />
		<input class="button" value="Login" type="submit" />
	<a href="index.php?page=register">Register</a> | <a href="index.php?page=resetpass">Forgot Password</a>
    </div>
	</form>

<? } else { ?>
Welcome back <strong><? echo 
$_SESSION['player_name']?></strong>. <a class="button" href="logout.php">Logout</a>
<? } ?>
</div>
