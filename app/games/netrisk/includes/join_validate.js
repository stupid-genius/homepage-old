if (document.forms[0].player.value==''){
	alert("Please fill out the Player Name field before submitting.");
	document.forms[0].player.focus();
} else if (document.forms[0].playerpassword1.value==''){
	alert("Please fill out the Password field before submitting.");
	document.forms[0].playerpassword1.focus();
} else if (document.forms[0].playerpassword2.value==''){
	alert("Please fill out the Re-Enter Password field before submitting.");
	document.forms[0].playerpassword2.focus();
} else if (!(document.forms[0].playerpassword1.value == document.forms[0].playerpassword2.value)){
	alert("Password fields do not match, please Re-Enter your password.");
	document.forms[0].playerpassword1.focus();
} else {
	document.getElementById('joingame').style.display = 'none';
	document.getElementById('creating').style.display = 'block';
	document.forms[0].submit();
}