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

include_once("include/adminalert.inc.php");//error messages
include_once("include/security.inc.php");
include_once("include/paths.inc.php");
include_once("include/html.inc.php");
include_once("include/formatting.inc.php");
include_once("include/dbfunctions.inc.php");//extract data from db

//check if magic quotes on, then stripslashes
magicQuotes();

$categoryID=sqlite_escape_string($_GET['id']);
$categoryName=sqlite_escape_string($_GET['name']);


$handle=dbOpen($dbPath);



if($categoryID!=NULL){
	
	$languagesByShortName=dbLanguagesByShortName($handle);

	$languageShort=sqlite_escape_string($_GET['language']);//ej: es, en ...

	$languagesByShortName=dbLanguagesByShortName($handle);
	$languagesByID=dbLanguagesByID($handle);
	$savedShowlanguages=dbNumberLanguages2Show($handle);

	if($languageShort!=NULL){
		

		if($languagesByShortName[$languageShort]['id']==1){//check if the shortname is the one from 'any' language (id=1)
			if($savedShowlanguages==0){//if settings set to show all languages -> redirect to link without 'all' language shortname
				$categoryData=dbCategoryData($handle,$categoryID);
				$newURL=$cmsPath;
				$newURL .= "category/" . string2Url($categoryData['name']) ."-" . $categoryID . "/" ;
				//header("Location: $newURL", true, 301);
				// close database file
				sqlite_close($handle);
				header("Location: $newURL");
				exit;
			}else{
				$showlanguages='0';
			}
		}else if($languagesByShortName[$languageShort]['id']==$savedShowlanguages){
				$categoryData=dbCategoryData($handle,$categoryID);
				$newURL=$cmsPath;
				$newURL .= "category/" . string2Url($categoryData['name']) ."-" . $categoryID . "/" ;
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
	
	$categoryID=sqlite_escape_string($categoryID);
	$categoryData=dbCategoryData($handle,$categoryID);
	if($categoryData==NULL){
		// close database file
		sqlite_close($handle);
		alertBox('error','ERROR','Category doesn\'t exist<br />To go back click ',$cmsPath);
	}
	if($categoryName!=string2Url($categoryData["name"])){
		$newURL=$cmsPath;
		if($languageShort!=NULL){$newURL.=$languageShort."/";}	
		$newURL.="category/" . string2Url($categoryData['name']) ."-" . $categoryID . "/" ;
		// close database file
		sqlite_close($handle);
		header("Location: $newURL", true, 301);
		exit;
	}
	
	if($languageShort!=NULL && $languagesByShortName[$languageShort]['id']!=$categoryData['languageID'] && $categoryData['languageID']!=1 && $languagesByShortName[$languageShort]['id']!=1){
		// 'aa' is the shortname of the default category
		$newURL=$cmsPath;
		$newURL.=$languagesByID[$categoryData['languageID']]['shortname']."/";		
		$newURL.="category/" . string2Url($categoryData['name']) ."-" . $categoryID . "/" ;
		// close database file
		sqlite_close($handle);
		header("Location: $newURL", true, 301);
		exit;
		
	}
	
	
	if($showlanguages!=NULL){//mostly in case default category is called with a nonexisting language

		$articlesCategory=dbArticlesCategory($handle,$categoryID,'date',$showlanguages);
		$categoriesList=dbCategoriesList($handle);
		$categoriesTree=dbCategoriesTree($handle,FALSE,$showlanguages);
		$categoryChildren=dbCategoryChildren($handle,$categoryID);
	}
	
}else{
	// close database file
	sqlite_close($handle);
	alertBox('error','ERROR','You must select a category<br />To go back click ',$cmsPath);
}

//CONFIG DATA
$webTitle=dbWebTitle($handle);

// all done
// close database file
//sqlite_close($handle);

?>