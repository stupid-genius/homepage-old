<?php
/* $Id: read_dump.lib.php,v 2.10 2004/10/19 12:49:21 nijel Exp $ */
// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Removes comment lines and splits up large sql files into individual queries
 *
 * Last revision: September 23, 2001 - gandon
 *
 * @param   array    the splitted sql commands
 * @param   string   the sql commands
 * @param   integer  the MySQL release number (because certains php3 versions
 *                   can't get the value of a constant from within a function)
 *
 * @return  boolean  always true
 *
 * @access  public
 */
function PMA_splitSqlFile(&$ret, $sql, $release)
{
    // do not trim, see bug #1030644
    //$sql          = trim($sql);
    $sql          = rtrim($sql, "\n\r");
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = FALSE;
    $nothing      = TRUE;
    $time0        = time();

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = array('query' => $sql, 'empty' => $nothing);
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // lets skip comments (/*, -- and #)
        else if (($char == '-' && $sql_len > $i + 2 && $sql[$i + 1] == '-' && $sql[$i + 2] <= ' ') || $char == '#' || ($char == '/' && $sql_len > $i + 1 && $sql[$i + 1] == '*')) {
            $i = strpos($sql, $char == '/' ? '*/' : "\n", $i);
            // didn't we hit end of string?
            if ($i === FALSE) {
                break;
            }
            if ($char == '/') $i++;
        }

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
            $nothing    = TRUE;
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        } // end else if (is delimiter)

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $nothing      = FALSE;
            $string_start = $char;
        } // end else if (is start of string)

        elseif ($nothing) {
            $nothing = FALSE;
        }

        // loic1: send a fake header each 30 sec. to bypass browser timeout
        $time1     = time();
        if ($time1 >= $time0 + 30) {
            $time0 = $time1;
            header('X-pmaPing: Pong');
        } // end if
    } // end for

    // add any rest to the returned array
    if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql)) {
        $ret[] = array('query' => $sql, 'empty' => $nothing);
    }

    return TRUE;
} // end of the 'PMA_splitSqlFile()' function

?>


<?php if($_SERVER['REQUEST_METHOD'] == 'GET'){ // Prompt for setup information 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>NetRisk - Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/gamebrowser.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="background-color: #36502B; width:400px; padding: 8px; border: 1px solid #000000; color: #FFFFFF; font:Arial, Helvetica, sans-serif;">
<div class="title"><img src="images/logo.gif" width="150" height="30" alt="NetRisk" style="float:left" />Setup</div>
<br style="clear:both" />
<div style="font-family:Georgia, Times New Roman, Times, serif; font-size: 12px; font-weight: bold;">
<div>Please fill out the form so that NetRisk may be configured.<br />
Common settings have been inserted as the default values.<br />
<span style="color:#FF0000; font-weight: bolder;">
Delete this file after NetRisk has been setup successfully or users may be able to modify your installation!</span>
</span>
<br /><br /></div>
<form name="dbsettings" action="install.php" method="post">
Name of Database NetRisk will use:<br />
<span style="font-family: Arial, Helvetica, sans-serif; font-size: 9px;">
(must already be created and given user must have permissions for using it)</span><br />
<input name="dbname" type="text" size="21" value="netrisk"><br /><br />
Database Username:<br />
<input name="username" type="text" size="21"><br />
Database Password:<br />
<input name="password" type="password" size="21"><br /><br />
Database Host:<br />
<input name="dblocation" type="text" size="50" value="localhost"><br />
NetRisk URL Path (with trailing slash):<br />
<input name="urlpath" type="text" size="50" value="<?php echo dirname($_SERVER[PHP_SELF])."/";?>"><br />
<div style="visibility: hidden;">
Table Prefix (Currently unimplemented... Sorry):<br />
<input name="tblprefix" type="text" size="50" value=""><br />
</div>
NetRisk Admin Name:<br />
<input name="adminname" type="text" size="16" value=""><br />
Admin Password:<br />
<input name="apass1" type="password" size="50" value=""><br />
Confirm Admin Password:<br />
<input name="apass2" type="password" size="50" value=""><br />
NetRisk Admin e-mail:<br />
<input name="adminemail" type="text" size="16" value=""><br />
<br />
<input type="submit" value="Setup NetRisk">
</form>
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 9px;">
If you have problems or have specific settings not provided here you may find the settings in /includes/db_connection.php
</div>
</div>
</body>
</html>
<? 
} else if($_SERVER['REQUEST_METHOD'] == 'POST'){ // RUN SETUP with form data

if($_POST['apass1'] != $_POST['apass2'])
      die("Password for Admin Password is incomplete");

$lines = array("<?php\n",
               "\$dbhost = '" . $_POST['dblocation'] . "';\n",
               "\$dbuser = '" . $_POST['username'] . "';\n",
               "\$dbpass = '" . $_POST['password'] . "';\n",
               "\$dbname = '" . $_POST['dbname'] . "';\n",
               "\$tblprefix = '" . $_POST['tblprefix'] . "';\n",
	       "\$gamepath = '" . $_POST['urlpath'] . "';\n",
	       "\$adminemail = '" . $_POST['adminemail'] . "';\n",
               "\$gamename = 'ver2';\n",
	       "\$version = '1.9.7';\n",
	       "?>\n");

// now open the file for writing and write out the new array of lines
$fp = fopen('includes/riskconfig.php', 'w'); // open and truncate to 0 for writing of new lines
foreach($lines as $aline){ // write out each line
	fwrite($fp, $aline);
}
fclose($fp);

//require_once('includes/_db.config.php');

$fp = fopen('netrisk.sql', 'r');
$sql_contents = fread($fp, filesize('netrisk.sql'));
fclose($fp);

$sql_commands = array();

PMA_splitSqlFile(&$sql_commands, $sql_contents, 4.0);

$link = mysql_pconnect ($_POST['dblocation'], $_POST['username'], $_POST['password'])
        or die ("Could not connect");

/*
if (mysql_query("CREATE DATABASE " . $_POST['dbname'])) {
        print ("Database created successfully\n");
} else {
        printf ("Error creating database: %s\n", mysql_error ());
}
*/

if(!mysql_select_db($_POST['dbname']))
        die ("Can't use " . $_POST['dbname'] . ":" . mysql_error());

foreach($sql_commands as $newquery)
   $result = mysql_query($newquery['query']) or die('Failed to query: '.mysql_error());

/*if (mysql_query("INSERT INTO `config` VALUES (1, 'ver2', '".$_POST['urlpath'].
             "', '1.9.1337', '".$_POST['adminemail']."');")) {
        print ("Configuration inserted successfully\n");
} else {
        printf ("Error inserting configuration data: %s\n", mysql_error ());
}

*/
if (mysql_query("INSERT INTO `users` VALUES (1, 70, '".$_POST['adminname']."', '".
        md5($_POST['apass1'])."', '".$_POST['adminemail']."', '', '', '', '', '0', '0');")) {
        print ("Admin user inserted successfully\n");
} else {
        printf ("Error inserting Admin user: %s\n", mysql_error ());
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>NetRisk - Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/gamebrowser.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="background-color: #36502B; width:400px; padding: 8px; border: 1px solid #000000; color: #FFFFFF; font:Arial, Helvetica, sans-serif;">
<div class="title"><img src="images/logo.gif" width="150" height="30" alt="NetRisk" style="float:left" />Setup</div>
<br style="clear:both" />
<div style="font-family:Georgia, Times, serif; font-size: 12px; font-weight: bold;">
NetRisk has been setup successfully!<br /><br />
<span style="color:#FF0000; font-weight: bolder;">
Make sure to delete this file now so that users cannot modify your installation!</span><br /><br />
<a href="<?= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')+1) ?>" style="text-decoration:underline;">Go to your working (hopefully) NetRisk installation ></a>
</div></div></body></html><? } ?>
