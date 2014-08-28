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


//init code for edit page

include_once("session.inc.php");
include_once("formatting.inc.php");

//check if magic quotes on, then stripslashes
magicQuotes();
$article=$_GET['article'];

//echo sqlite_libversion();

//CONFIG DATA
$webTitle=dbWebTitle($handle);
$show=dbNumber2Show($handle);
//$numberArticles=dbNumberArticles($handle);


$usersList=dbUsersList($handle);
$languagesList=dbLanguagesList($handle);

if(!is_null($article)){
	$article=sqlite_escape_string($article);
	$articleData=dbArticleData($handle,$article);
	$categoriesList=dbCategoriesList($handle,true,$articleData["languageID"]);
	$articleCategories=dbArticleCategories($handle,$article);
	if($articleData['statusID']==1){
	$status="Draft";
	}else{
	$status="Published";
	}
	list($year, $month, $day, $hour) = string2Date($articleData["date"]);
}else{
	$categoriesList=dbCategoriesList($handle,true,2);//2 is english, default language?
	$articleCategories[0]=1;
	$status="Draft";
}//end if article


// all done
// close database file
dbClose($handle);

?>