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


//init code for index articles page

include_once("include/adminalert.inc.php");
include_once("include/security.inc.php");
include_once("include/paths.inc.php");
include_once("include/html.inc.php");
include_once("include/formatting.inc.php");
include_once("include/dbfunctions.inc.php");//extract data from db

//check if magic quotes on, then stripslashes
magicQuotes();

$offset=$_GET['offset'];
if(!is_null($offset)){
	$offset=sqlite_escape_string($offset);
}else{
	$offset=0;
}

$handle=dbOpen($dbPath);

//CONFIG DATA
$webTitle=dbWebTitle($handle);
$show=dbNumber2Show($handle);
$languagesByShortName=dbLanguagesByShortName($handle);

$languageShort=sqlite_escape_string($_GET['language']);//ej: es, en ...
$savedShowlanguages=dbNumberLanguages2Show($handle);

if($languageShort!=NULL){

	
	if($languagesByShortName[$languageShort]['id']==1){//check if the shortname is the one from 'any' language (id=1)
		if($savedShowlanguages==0){//if settings set to show all languages -> redirect to link without 'all' language shortname
			$newURL=$cmsPath;
			//header("Location: $newURL", true, 301);
			// close database file
			sqlite_close($handle);
			header("Location: $newURL");
			exit;
		}else{
			$showlanguages='0';
		}
	}else if($languagesByShortName[$languageShort]['id']==$savedShowlanguages){
		$newURL=$cmsPath;
			//header("Location: $newURL", true, 301);
			// close database file
			sqlite_close($handle);
			header("Location: $newURL");
			exit;
	}else{
		$showlanguages=$languagesByShortName[$languageShort]['id'];
	}
	
	//echo $languagesByShortName[$language]['name'];
	//echo $languagesByShortName[$languageShort]['id'];
}else{
	$showlanguages=dbNumberLanguages2Show($handle);
}


if($showlanguages!=NULL){//mostly in case default category is called with a nonexisting language
		$numberArticles=dbNumberArticles($handle,$showlanguages);
		//ARTICLES
		//show only first half of the text and only published articles
		$articlesData=dbArticlesData($handle,$offset,$show,true,false,$showlanguages,'date_till_now');
		$categoriesList=dbCategoriesList($handle);
		$categoriesTree=dbCategoriesTree($handle,FALSE,$showlanguages);
}
	
$usersList=dbUsersListFullName($handle);



?>