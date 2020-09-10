var sOps = '!&\\|\\^\\?\\(\\)';
var sOpPattern = '\\s*['+sOps+']';
var sTermPattern = '\\s*[^'+sOps+'\\s]+\\s*';

/*
*	JavaScript Truth Table
*	creates a truth table in HTML from a given expression
*	written by: Allen Ng
*/
function JSTruthTable(expression)
{
	sTTable = "";
	var lExpression = new JSlogicExpression(expression);
	this.writeTable = function(output)
	{
		sTTable += output;
	}
	this.getTruthTable = function()
	{
		var sCaption = '<caption>Truth table for '+lExpression.getString(this.writeTable)+'</caption>';
		lExpression.evaluate(this.writeTable);
		return '<table border="1" align="center">'+sCaption+sTTable+'</table>';
	}
}

/*
*	LogicalExpression
*	handles processing of a logical expression
*/
function JSlogicExpression(exp)
{
	LE_iCount = 0;
	var sPFExp = toPostfix(exp);
	var LEroot = parseExp(sPFExp);
	this.evaluate = LEEval;
	this.getString = LEtoString;
	function LEEval(writer)
	{
		iPowerSet = Math.pow(2,LE_iCount);
		for(i=iPowerSet-1;i>=0;--i)
		{
			LE_bfState = i;
			writer('<tr>');
			LEroot.evaluate(writer);
			writer('</tr>');
		}
	}
	function LEtoString(writer)
	{
		writer('<tr>');
		var sOutput = LEroot.getString(writer);
		writer('</tr>');
		return sOutput;
	}
}
function toPostfix(infixExp)
{
	var opStack = new JStack();
	var scanExp = new JStringScanner(infixExp);
	var sPostfixExp = "";
	
	while(scanExp.hasNext())
		if(!scanExp.hasNextExp(sOpPattern))
			sPostfixExp += scanExp.nextExp(sTermPattern)+' ';
		else
		{
			var sToken = scanExp.nextExp(sOpPattern);
			switch(sToken)
			{
			case '(':
				opStack.push(sToken);
				break;
			case '!':
				opStack.push(sToken);
				break;
			case '&':
				while(!opStack.isEmpty() && opStack.peek() != '(')
					sPostfixExp += opStack.pop()+" ";
				opStack.push(sToken);
				break;
			case '|':
			case '^':
				while(!opStack.isEmpty() && opStack.peek() != '(' && opStack.peek() != '&')
					sPostfixExp += opStack.pop()+" ";
				opStack.push(sToken);
				break;
			case '?':
				while(!opStack.isEmpty() && opStack.peek() != '('
				&& opStack.peek() != '&' && opStack.peek() != '|'
				&& opStack.peek() != '^')
					sPostfixExp += opStack.pop()+" ";
				opStack.push(sToken);
				break;
			case ')':
				while(!opStack.isEmpty() && opStack.peek() != '(')
					sPostfixExp += opStack.pop()+" ";
				opStack.pop();
				if(!opStack.isEmpty() && opStack.peek() == '!')
					sPostfixExp += opStack.pop()+" ";
				break;
			default:
				throw "Unsupported operation";
			}
		}

	while(!opStack.isEmpty())
		sPostfixExp += opStack.pop()+" ";

	return sPostfixExp;
}
function parseExp(postFixExp)
{
	var treeStack = new JStack();
	var scanExp = new JStringScanner(postFixExp);
	var aOpnd = new Array();
	
	while(scanExp.hasNext())
		if(!scanExp.hasNextExp(sOpPattern))
		{
			var sNextExp = scanExp.nextExp(sTermPattern);
			var newNode;
			var opNode = null;
			
			for(i=0; i<aOpnd.length; ++i)
			{
				if(aOpnd[i].getData() == sNextExp)
					opNode = aOpnd[i];
			}
				
			if(opNode == null)
			{
				newNode = new LEoperand(sNextExp);
				aOpnd.push(newNode);
			}
			else
				newNode = opNode;
				
			treeStack.push(newNode);
		}
		else
		{
			var opNode = new LEoperator(scanExp.nextExp(sOpPattern));
			if(!treeStack.isEmpty())
			{
				if(opNode.getData() != '!')
					opNode.setRight(treeStack.pop());
			}
			else
				throw "exception";
			if(!treeStack.isEmpty())
				opNode.setLeft(treeStack.pop());
			else
				throw "exception";
				
			treeStack.push(opNode);
		}
	
	return treeStack.pop();
}

LEoperator.prototype = new JBTNode();
LEoperator.prototype.evaluate = LEOptrEval;
LEoperator.prototype.getString = LEOptrToString;
/*
*	Operator
*	represents a node within a logical expression tree
*/
function LEoperator(operation)
{
	this.setData(operation);
}
function LEOptrEval(writer)
{
	var bResult;
	this.lastState;
	
	var op = this.getData();
	var firstOpnd = this.getLeft().evaluate(writer);
	if(op=='!')
		bResult = eval(op+firstOpnd);
	else
	{
		var secOpnd = this.getRight().evaluate(writer);
		if(op=='?')
			bResult = factorImplication(firstOpnd, secOpnd);
		else if(op=='^')
			bResult = eval(firstOpnd+op+secOpnd);
		else
			bResult = eval(firstOpnd+op+op+secOpnd);
	}
	
	if(this.lastState != LE_bfState)
	{
		writer('<td>'+(bResult?true:false)+'</td>');
		this.lastState = LE_bfState;
	}
	return bResult;
}
function factorImplication(firstOp, secOp)
{
	return eval('!'+firstOp+'|'+secOp);
}
function LEOptrToString(writer)
{
	var sResult;
	switch(this.getData())
	{
	case '!':
		sResult = "&not;"+this.getLeft().getString(writer);
		break;
	case '&':
		sResult = "("+this.getLeft().getString(writer)+"&and;"+this.getRight().getString(writer)+")";
		break;
	case '|':
		sResult = "("+this.getLeft().getString(writer)+"&or;"+this.getRight().getString(writer)+")";
		break;
	case '^':
		sResult = "("+this.getLeft().getString(writer)+"&oplus;"+this.getRight().getString(writer)+")";
		break;
	case '?':
		sResult = "("+this.getLeft().getString(writer)+"&rarr;"+this.getRight().getString(writer)+")";
		break;
	}

	if(!this.bToggle)
	{
		writer('<th>'+sResult+'</th>');
		this.bToggle = true;
	}
	
	return sResult;
}

LEoperand.prototype = new JBTNode();
LEoperand.prototype.evaluate = LEOpndEval;
LEoperand.prototype.getString = LEOpndToString;
/*
*	class variables
*/
var LE_iCount = 0;
var LE_bfState = 0;
/*
*	Operand
*	represents a leaf in a Logical Expression tree
*/
function LEoperand(newData)
{
	this.iIndex = LE_iCount++;
	this.setData(newData);
}
function LEOpndEval(writer)
{
	this.lastState;
	var bValue = LE_bfState&(1<<this.iIndex)?true:false;
	if(this.lastState != LE_bfState)
	{
		writer('<td>'+bValue+'</td>');
		this.lastState = LE_bfState;
	}
	return bValue;
}
function LEOpndToString(writer)
{
	var sOutput = this.getData();
	if(!this.bToggle)
	{
		writer('<th>'+sOutput+'</th>');
		this.bToggle = true;
	}
	return sOutput;
}