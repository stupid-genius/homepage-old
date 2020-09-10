/***************************************
*	JavaScript Binary Search Tree
*	written by: Allen Ng
***************************************/
function jsBST()
{
	var root;
	this.get = JBTSearch;
	this.add = JBTAdd;
	this.remove = JBTRemove;

	function JBTSearch(key)
	{
		var node = findNode(root, key);
		if(node!=null)
			node = node.getData();
		return node;
	}
	function findNode(node, key)
	{
		if(node==null)
			return null;

		var curNode = node.getData();
		
		if(key == curNode)
			return node;
		if(key < curNode)
			return findNode(node.getLeft(), key);
		if(key > curNode)
			return findNode(node.getRight(), key);
	}
	function findMin(node)
	{
		var minNode;
		if(node.getLeft() == null)
			minNode = node;
		else
			minNode = findMin(node.getLeft());
		return minNode;
	}
	function findMax(node)
	{
		var maxNode;
		if(node.getRight() == null)
			maxNode = node;
		else
			maxNode = findMax(node.getRight());
		return maxNode;
	}
	function JBTAdd(value)
	{
		root = insert(root, value);
	}
	function insert(node, value)
	{
		if(node==null)
			return new jsBTNode(value);
			
		var curNode = node.getData();

		if(value < curNode)
		{
			var temp = insert(node.getLeft(), value);
			node.setLeft(temp);
			temp.setParent(node);
		}
		else if(value > curNode)
		{
			var temp = insert(node.getRight(), value);
			node.setRight(temp);
			temp.setParent(node);
		}
		return node;
	}
	function JBTRemove(value)
	{
		var node = findNode(root, value);
		
		if(node!=null)
			unlink(node);
	}
	function link(parent, node)
	{
		if(parent==null)
		{
			root = node;
			return;
		}
		node.setParent(parent);
		if(node.getData() < parent.getData())
			parent.setLeft(node);
		if(node.getData() > parent.getData())
			parent.setRight(node);
	}
	function unlink(node)
	{
		if(node.getLeft()!=null)
		{
			if(node.getRight()!=null)
			{
				// node has two children
				var maxNode = findMax(node.getLeft());
				unlink(maxNode);
				link(node.getParent(), maxNode);
			}
			else
				// node has a left child
				link(node.getParent(), node.getLeft());
		}
		else if(node.getRight()!=null)
			// node has a right child
			link(node.getParent(), node.getRight());
		else
		{
			if(node == root)
				return;
			// node has no children
			if(node == node.getParent().getLeft())
				node.getParent().setLeft(null);
			else if(node == node.getParent().getRight())
				node.getParent().setRight(null);
			node.setParent(null);
		}
	}
}

jsBTNode.prototype.setParent = JBTNSetParent;
jsBTNode.prototype.setLeft = JBTNSetLeft;
jsBTNode.prototype.setRight = JBTNSetRight;
jsBTNode.prototype.setData = JBTNSetData;
jsBTNode.prototype.getParent = JBTNGetParent;
jsBTNode.prototype.getLeft = JBTNGetLeft;
jsBTNode.prototype.getRight = JBTNGetRight;
jsBTNode.prototype.getData = JBTNGetData;

function jsBTNode(newData)
{
	var data;
	var parent;
	var leftChild;
	var rightChild;
	
	this.setData(newData);
}
function JBTNSetParent(node)
{
	this.parent = node;
}
function JBTNSetLeft(node)
{
	this.leftChild = node;
}
function JBTNSetRight(node)
{
	this.rightChild = node;
}
function JBTNSetData(newData)
{
	this.data = newData;
}

function JBTNGetParent()
{
	return this.parent;
}
function JBTNGetLeft()
{
	return this.leftChild;
}
function JBTNGetRight()
{
	return this.rightChild;
}
function JBTNGetData()
{
	return this.data;
}