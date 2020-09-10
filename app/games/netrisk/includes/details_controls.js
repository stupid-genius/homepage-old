function msgoption(){
	document.getElementById('status').style.display = 'none';
	document.getElementById('options').style.display = 'none';
	document.getElementById('messages').style.display = 'block';
}
function optoption(){
	document.getElementById('status').style.display = 'none';
	document.getElementById('messages').style.display = 'none';
	document.getElementById('options').style.display = 'block';
}
function statusoption(){
	document.getElementById('messages').style.display = 'none';
	document.getElementById('options').style.display = 'none';
	document.getElementById('status').style.display = 'block';
}
// Messages Options
function sendmsgtab(){
	document.getElementById('inbox').style.display = 'none';
	document.getElementById('sendmsg').style.display = 'block';
}
function inboxtab(){
	document.getElementById('inbox').style.display = 'block';
	document.getElementById('sendmsg').style.display = 'none';
}