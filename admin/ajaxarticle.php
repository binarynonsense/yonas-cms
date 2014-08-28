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
 
//some AJAX magic is used to retrieve this data from article.php

//include_once("session.inc.php");
include_once("../include/formatting.inc.php");
include_once("../include/session.inc.php");

// set path of database file
$dbPath = "yonas.db";
$handle=dbOpen($dbPath);
//check if magic quotes on, then stripslashes
magicQuotes();
$languageID=$_GET['language'];
if($_GET['article']!=NULL){
	$article=$_GET['article'];
	$articleCategories=dbArticleCategories($handle,$article);
}else{
	$articleCategories[0]=1;
}




$categoriesList=dbCategoriesList($handle,true,$languageID);

// all done
// close database file
dbClose($handle);

foreach ($categoriesList as $key => $value){
	echo "<input type='checkbox' value='$key' name=\"categories[]\" ";
	//init
	$empty=true;
	foreach($articleCategories as $articleCategory){
		if ($articleCategory==$key){$empty=false;}
	}
	if($empty){
		$articleCategories[0]=1;
	}
	//end
	//instead of the above, maybe it would be better to check if the language of the article is the same as the language asked for
	//if not, $articleCategories[0]=1;
	foreach($articleCategories as $articleCategory){
		if ($articleCategory==$key){echo "checked='yes'";}
	}
	//}
	echo"> $value<br />";
}

?>