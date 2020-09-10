<head>
<title>News Post</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="styles/styles.css">
<script language="JavaScript">

function checkForm()
{
	// the variables below are assigned to each
	// form input 
	var gname, gtitle, gicon, gmessage;
	with(window.document.guestform)
	{
		gname    = txtName;
		gtitle   = txtTitle;
		gicon     = txtIcon;
		gmessage = mtxMessage;
	}
	
	// if name is empty alert the visitor
	if(trim(gname.value) == '')
	{
		alert('Please enter your name');
		gname.focus();
		return false;
	}
	// if name is empty alert the visitor
	if(trim(gtitle.value) == '')
	{
		alert('Please provide a Title');
		gtitle.focus();
		return false;
	}

	// alert the visitor if message is empty
	else if(trim(gmessage.value) == '')
	{
		alert('Please enter your message');
		gmessage.focus();
		return false;
	}
	else
	{
		// when all input are correct 
		// return true so the form will submit		
		return true;
	}
}

/*
Strip whitespace from the beginning and end of a string
Input  : a string
Output : the trimmed string
*/
function trim(str)
{
	return str.replace(/^\s+|\s+$/g,'');
}

/*
Check if a string is in valid email format. 
Input  : the string to check
Output : true if the string is a valid email address, false otherwise.
*/
</script>
</head>
<body>
<?php  
if (isset($_SESSION['player_name']))
{ ?>
<h3>Post News</h3>
<form method="post" name="newsform" action="admin/news_action.php">
 <table width="550" border="0" cellpadding="2" cellspacing="1">
  <tr> 
   <td width="100">Name :</td> <td> <strong><?= $_SESSION['player_name'];?></strong>
    <input name="txtName" type="hidden" id="txtName" size="30" 
value="<?=$_SESSION['player_name'];?>" /></td>
 </tr>
  <tr> 
   <td width="100">Title</td>
   <td> 
    <input class="tinput" name="txtTitle" type="text" id="txtTitle" maxlength="75"></td>
 </tr>

  <tr> 
   <td width="100">Message </td> <td> 
    <textarea class="newstxt" name="mtxMessage" cols="50" rows="5" id="mtxMessage"></textarea></td>
 </tr>
  <tr> 
   <td width="100">&nbsp;</td>
   <td> 
    <input class="subnews" name="btnSign" type="submit" id="btnSign" value="Post News" ></td>
 </tr>
</table>
</form>
<br>

<?} else {?>

<h3> You Must Login to Post News</h3>
<? } ?>

