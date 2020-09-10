
<?php
// =======================
// Show news entries
// =======================
require_once('includes/_db.config.php');

// how many news entries to show per page
$rowsPerPage = 10;

// by default we show first page
$pageNum = 1;

// if $_GET['pgnum'] defined, use the value as page number
if(isset($_GET['pgnum'])) {
	$pageNum = $_GET['pgnum'];
}
// counting the offset ( where to start fetching the entries )
$offset = ($pageNum - 1) * $rowsPerPage;

$sql = "SELECT id, name, title, message, DATE_FORMAT(entry_date,'%d.%m.%Y') FROM news ORDER BY id DESC 
LIMIT $offset, $rowsPerPage";

// execute the query 
$result = mysql_query($sql) or die('Error, select failed. ' . mysql_error());

?>

<?php
// if the news is empty show a message
if(mysql_num_rows($result) == 0)
{
?>
<!--MW: removed brs-->
<p>No current News post. </p>
<?php
}
else
{
	// get all news entries
	while($row = mysql_fetch_array($result))
	{
		// list() is a convenient way of assign a list of variables
		// from an array values 
		list($id, $name, $title, $message, $date) = $row;

		// change all HTML special characters,
		// to prevent some nasty code injection
		$name    = htmlspecialchars($name);
		$message = htmlspecialchars($message);		

		// convert newline characters ( \n OR \r OR both ) to HTML break tag ( <br> )
		$message = nl2br($message);
?>
<?php /*MW: should you have another end div here? the news posts are nesting within each other as is*/ ?>

<div class="news">
	<div class="news_ttl"><?=$title;?></div>
	<div class="news_name"><?=$name;?></div>
	<div class="news_msg"><?=$message;?></div>
	<div class="news_date">Posted : <?=$date;?></div>
</div>

<?php
	} // end while
} //end else
// below is the code needed to show page numbers

// count how many rows we have in database
$query   = "SELECT COUNT(id) AS numrows FROM news";
$result  = mysql_query($query) or die('Error, count failed. ' . mysql_error());
$row     = mysql_fetch_array($result, MYSQL_ASSOC);
$numrows = $row['numrows'];

// how many pages we have when using paging?
$maxPage  = ceil($numrows/$rowsPerPage);
$nextLink = '';

// show the link to more pages ONLY IF there are 
// more than one page
if($maxPage > 1)
{
	// this page's path
	$self     = $_SERVER['PHP_SELF'];
	
	// we save each link in this array
	$nextLink = array();
	
	// create the link to browse from page 1 to page $maxPage
	for($pgnum = 1; $pgnum <= $maxPage; $pgnum++)
	{
		$nextLink[] =  "<a 
href=\"$self?page=news&pgnum=$pgnum\">$pgnum</a>";
	}
	
	// join all the link using implode() 
	$nextLink = "Go to page : " . implode(' &raquo; ', $nextLink);
}

?>
<div class="news_pg"><?=$nextLink;?></div>


