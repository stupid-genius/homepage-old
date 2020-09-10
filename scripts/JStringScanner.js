/***************************************
*	JavaScript String Scanner
*	implements idea similar to Java's Scanners
*	author: Allen Ng
***************************************/
function JStringScanner(string)
{
	var sString = string;
	var iCurIndex = 0;
	var cDelimiter = "\s";
	var reDelimPattern = "";
	this.hasNext = function()
	{
		return sString.substring(iCurIndex).search(/^\s*[^\s]+/)==-1?false:true;
	}
	this.hasNextExp = function(pattern)
	{
		return sString.substring(iCurIndex).search('^'+pattern)==-1?false:true;
	}
	this.next = function()
	{
		return sString.charAt(iCurIndex++);
	}
	this.nextExp = function(pattern)
	{
		var sMatch = sString.substring(iCurIndex).match('^'+pattern);
		iCurIndex += sMatch[0].length;
		sMatch = sMatch[0].replace(/\s+/g, '');
		return sMatch;
	}
}