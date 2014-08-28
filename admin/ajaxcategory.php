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

//some AJAX magic is used to retrieve this data from category.php

include_once("../include/formatting.inc.php");
include_once("../include/session.inc.php");

// set path of database file
$dbPath = "yonas.db";
$handle=dbOpen($dbPath);
//check if magic quotes on, then stripslashes
magicQuotes();
$languageID=$_GET['language'];

$parentCategoriesList=dbCategoriesList($handle,false,$languageID);

if($_GET['category']!=NULL){
	$category=$_GET['category'];
	$category=sqlite_escape_string($category);
	$categoryData=dbCategoryData($handle,$category);
	$categoryChildren=dbCategoryChildren($handle,$category);
}


// all done
// close database file
dbClose($handle);
?>

<select name="parent">
	<option value='0' 'selected'>No parent</option>

<?php	
	//don't let a parent change to children if it has children of its own:
	if(is_null($categoryChildren)){	
		foreach ($parentCategoriesList as $key => $value){
			if($key!=$category && $key != 1){
				echo "<option value='$key' ";
				if($key==$categoryData['parentID']) echo "selected";
				echo ">$value</option>";
			}
		}
	}
?>
</select>