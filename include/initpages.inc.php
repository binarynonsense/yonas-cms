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


//init code for category page

include_once("include/adminalert.inc.php");
include_once("include/security.inc.php");
include_once("include/paths.inc.php");
include_once("include/html.inc.php");
include_once("include/formatting.inc.php");
include_once("include/dbfunctions.inc.php");//extract data from db

//check if magic quotes on, then stripslashes
magicQuotes();

$pageID=sqlite_escape_string($_GET['id']);
$pageName=sqlite_escape_string($_GET['name']);
$languageShort=sqlite_escape_string($_GET['language']);//ej: es, en ...


$handle=dbOpen($dbPath);

$languagesByShortName=dbLanguagesByShortName($handle);
$languagesByID=dbLanguagesByID($handle);



if($pageID!=NULL){
	
	//CONFIG DATA
	$webTitle=dbWebTitle($handle);
	$savedShowlanguages=dbNumberLanguages2Show($handle);
	
	/*if($languageShort!=NULL){
	
		if($languagesByShortName[$languageShort]['id']==1){//check if the shortname is the one from 'any' language (id=1)
			if($savedShowlanguages==0){//if settings set to show all languages -> redirect to link without 'all' language shortname
			
				$pageData=dbPageData($handle,$pageID);
				list($year, $month, $day, $hour) = string2Date($pageData['date']);
				$newURL = $cmsPath . "$year/$month/$day/" . string2Url($pageData['title']) ."-" . $pageID . "/" ;
				//header("Location: $newURL", true, 301);
				header("Location: $newURL");
				exit;
			}else{
				$showlanguages='0';
			}
		}else if($languagesByShortName[$languageShort]['id']==$savedShowlanguages){
				$pageData=dbPageData($handle,$pageID);
				list($year, $month, $day, $hour) = string2Date($pageData['date']);
				$newURL = $cmsPath . "$year/$month/$day/" . string2Url($pageData['title']) ."-" . $pageID . "/" ;
				//header("Location: $newURL", true, 301);
				header("Location: $newURL");
				exit;
		}else{
			$showlanguages=$languagesByShortName[$languageShort]['id'];
		}
		
	}else{*/
		$showlanguages=dbNumberLanguages2Show($handle);
	//}

	if($showlanguages!=NULL){//mostly in case it is called with a nonexisting language
			$pageData=dbPageData($handle,$pageID);
			$categoriesList=dbCategoriesList($handle);
			$categoriesTree=dbCategoriesTree($handle,FALSE,$showlanguages);
	}
	
	//$pageID=sqlite_escape_string($pageID);
	
	
	if($pageData==NULL){
		// close database file
		sqlite_close($handle);
		alertBox('error','ERROR','page doesn\'t exist<br />To go back click ',$cmsPath);
	}
	
	list($year, $month, $day, $hour) = string2Date($pageData['date']);

	if($pageName!=string2Url($pageData['title'])){

		$newURL=$cmsPath;
		if($languageShort!=NULL){$newURL.=$languageShort."/";}		
		$newURL.=string2Url($pageData['title']) ."-" . $pageID . "/" ;
		// close database file
		sqlite_close($handle);
		header("Location: $newURL", true, 301);
		exit;
	}

	
}else{
	// close database file
	sqlite_close($handle);
	alertBox('error','ERROR','You must select an page<br />To go back click ',$cmsPath);
	exit;
}




$usersList=dbUsersListFullName($handle);
$userName=$usersList[$pageData['authorID']];
$replace = array ("<!-- more -->"=>"<a name='more'></a>");
$pageData['text']=strtr($pageData['text'], $replace);

// all done
// close database file
sqlite_close($handle);

?>