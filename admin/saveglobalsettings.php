<?php
/********************************************************************  

Yonas CMS
Copyright (C) 2008 Alvaro Garcia Cuesta

This file is part of Yonas CMS.

Yonas CMS is free software; you can redistribute it and/or modify it 
under the terms of the GNU General Public License version 2 as 
published by the Free Software Foundation.

Yonas CMS is distributed in the hope that it will be useful, but 
WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*********************************************************************/

//This file receives the neccesary info from edit.php to save an article in the database

include_once("../include/session.inc.php");

//check if magic quotes on, then stripslashes
magicQuotes();


$webTitle=sqlite_escape_string(utf8_encode ($_POST['webtitle']));
$show=sqlite_escape_string(utf8_encode ($_POST['show']));
$showlanguages=sqlite_escape_string(utf8_encode ($_POST['showlanguages']));

if(!isNumber($show)){
	alertBox('error','ERROR',' Number of articles to show <b>must be a number</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
}


	
sqlite_query($handle, "UPDATE configuration SET value='$webTitle' WHERE name='title'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));

$message.= "Title updated<br />";

sqlite_query($handle, "UPDATE configuration SET value='$show' WHERE name='show'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));

$message.= "Number to Show updated<br />";

sqlite_query($handle, "UPDATE configuration SET value='$showlanguages' WHERE name='showlanguages'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));

$message.= "Number languages to Show updated<br />";

alertBox('ok','Global Settings successfully updated',$message.'.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	

// all done
// close database file
sqlite_close($handle);




?>
