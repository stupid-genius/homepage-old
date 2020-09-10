/*************************************************
**	jsTable
**	a two-dimensional array or an array of arrays
**	(i.e. a table)
**	author: Allen Ng
*************************************************/
/*
*	TO DO:
*	add regex support
*	can't delete single row from subset if multiple rows match criteria
*/
function jsTable()
{
	/*
	*	Add row to bottom of table
	*/
	this.add = function(row)
	{
		dataTable.push(row);
	}
	this.addAt = function(row, index)
	{
		for(var i = dataTable.length; i>index; --i)
			dataTable[i] = dataTable[i-1];
		dataTable[index] = row;
	}
	/*
	*	Return row at index
	*/
	this.get = function(index)
	{
		return dataTable[index];
	}
	/*
	*	Remove all rows where keyCol = target
	*/
	this.remove = function(keyCol, target)
	{
		var deadRow = findIndex(keyCol, target);
		for(var i in deadRow)
			removeAt(deadRow[i]);
	}
	this.removeAt = function(index)
	{
		for(var i = index; i<dataTable.length-1; ++i)
			dataTable[i] = dataTable[i+1];
		dataTable.pop();
	}
	/*
	*	Returns a jsTable object containing all
	*	rows where keyCol = target
	*/
	this.find = function(keyCol, target)
	{
		var wa = new jsTable();
		var rows = findIndex(keyCol, target);
		for(var i in rows)
			wa.add(rows[i]);
		return wa;
	}
	/*
	*	Perform destructive Quicksort
	*/
	this.sort = function(keyCol)
	{
		quickSort(keyCol, 0, dataTable.length-1);
	}
	/*
	*	Returns the table as a string
	*	with delimiters inserted
	*/
	this.toString = function()
	{
		var sTable = "";
		if(this.withHeader)
		{
			var rowHdr = "";
			for(var n in dataTable[0])
				rowHdr += sHdrDelim.replace(/\[jstdHdr\]/, n);
			sTable += sRowDelim.replace(/\[jstdRow\]/, rowHdr);
		}
		for(var r in dataTable)
		{
			var dataRow = dataTable[r];
			var sRow = "";
			for(var c in dataRow)
				sRow += sColDelim.replace(/\[jstdCol\]/, dataRow[c]);
			sTable += sRowDelim.replace(/\[jstdRow\]/, sRow);
		}
		return sTabDelim.replace(/\[jstdTab\]/, sTable);
	}
	/*
	*	Sets the various delimiters
	*	format:
	*		sTabDelim[jstd]sHdrDelim[jstd]sRowDelim[jstd]sColDelim
	*	delimiter format:
	*		.*DELIM_ID.*
	*	DELIM_ID:
	*		table -> [jstdTab]
	*		header -> [jstdHdr]
	*		row -> [jstdRow]
	*		column -> [jstdCol]
	*	example:<table>[jstdTab]</table>[jstd]<th>[jstdHdr]</th>[jstd]<tr>[jstdRow]</tr>[jstd]<td>[jstdCol]</td>
	*/
	this.setDelim = function(d)
	{
		var delims = d.split(/\[jstd\]/);
		sTabDelim = delims[0];
		sHdrDelim = delims[1];
		sRowDelim = delims[2];
		sColDelim = delims[3];
	}

	// private
	/*
	*	Returns an array of row indexes
	*	where keyCol = target
	*/
	function findIndex(keyCol, target)
	{
		var aIndexes = new Array();
		// if sorted on the keyCol, then use binary search
		if(sCurSort==keyCol)
		{
			var iLength = dataTable.length;
			var begin = 0;
			var end = iLength-1;
			var cursor;
			while(end-begin>0)
			{
				cursor = (end-begin)/2 + begin;
				var curElem = dataTable[cursor][keyCol];
				if(curElem==target)
				{
					if(dataTable[cursor-1][keyCol]!=target)	// we've found the beginning
					{
						// now find all of them
						while(curElem==target)
							aIndexes.push(cursor);
						break;
					}
					end = cursor;
				}
				else
				{
					if(curElem<target)
						begin = cursor;
					else
						end = cursor;
				}
			}
		}
		// else use table scan
		else
		{
			for(var i=0;i<dataTable.length;++i)
				if(dataTable[cursor][keyCol]==target)
					aIndexes.push(i);
		}
		return aIndexes;
	}
	/*
	*	
	*/
	function repForm(a)
	{
		if(isNaN(a))
			a = a.toUpperCase();
		else
			a = parseInt(a);
		return a;
	}
	/*
	*	
	*/
	function partition(keyCol, left, right)
	{
		var i = left,
			j = right;
		var temp;
		var pivot = dataTable[Math.floor((left+right)/2)][keyCol];

		while(i<=j)
		{
			while(repForm(dataTable[i][keyCol])<repForm(pivot)) ++i;
			while(repForm(dataTable[j][keyCol])>repForm(pivot)) --j;
			if(i<=j)
			{
				temp = dataTable[i];
				dataTable[i] = dataTable[j];
				dataTable[j] = temp;
				i++;
				j--;
			}
		}
		return i;
	}
	/*
	*	
	*/
	function quickSort(keyCol, left, right)
	{
		var index = partition(keyCol, left, right);
		if(left < index-1)
			quickSort(keyCol, left, index-1);
		if(index < right)
			quickSort(keyCol, index, right);
	}

	var dataTable = new Array();
	var sTabDelim = "[jstdTab]";
	var sHdrDelim = "[jstdHdr]";
	var sRowDelim = "[jstdRow];";
	var sColDelim = "[jstdCol],";
	var sCurSort;
	// public
	this.withHeader = false;
	this.jstdHTML = "<table border='1'>[jstdTab]</table>[jstd]<th>[jstdHdr]</th>[jstd]<tr>[jstdRow]</tr>[jstd]<td>[jstdCol]</td>";
}