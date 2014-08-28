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

$languageShort=sqlite_escape_string($_GET['language']);//ej: es, en ...

//security check
if($_GET['year']!=NULL){
	$year=sqlite_escape_string($_GET['year']);
	if(!isNumber($year)){
		alertBox('error','ERROR','Year must be a valid number<br />To go back click ',$cmsPath);
	}	
	$link=$year."/";
}

if($_GET['month']!=NULL){
	$month=sqlite_escape_string($_GET['month']);
	if(!isNumber($month) || $month>12 || $month<1){
		alertBox('error','ERROR','Month must be a valid number<br />To go back click ',$cmsPath);
	}	
	$link.=$month."/";
}

if($_GET['day']!=NULL){
	$day=sqlite_escape_string($_GET['day']);
	if(!isNumber($day) || $day>31 || $day<1){
		alertBox('error','ERROR','Day must be a valid number<br />To go back click ',$cmsPath);
	}	
	$link.=$day."/";
}
/*
year month and date: articles date between yearmonthday000000 and yearmonthday235959
year month: articles date between yearmonth00000000 and yearmonth31235959
year: articles date between year0000000000 and year1231235959
*/
if($day!=NULL){
	$initDate=$day."000000";
	$endDate=$day."235959";
}else{
	$initDate="00000000";
	$endDate="31235959";
}
if($month!=NULL){
	$initDate=$month.$initDate;
	$endDate=$month.$endDate;
}else{
	$initDate="01".$initDate;
	$endDate="12".$endDate;
}
if($year!=NULL){
	$initDate=$year.$initDate;
	$endDate=$year.$endDate;
}else{
	alertBox('error','ERROR','I don\'t know how the hell you got here :-)<br />To go back click ',$cmsPath);
}

//echo $initDate . " - " . $endDate;


$handle=dbOpen($dbPath);

$languagesByShortName=dbLanguagesByShortName($handle);
$savedShowlanguages=dbNumberLanguages2Show($handle);

if($languageShort!=NULL){

	if($languagesByShortName[$languageShort]['id']==1){//check if the shortname is the one from 'any' language (id=1)
		if($savedShowlanguages==0){//if settings set to show all languages -> redirect to link without 'all' language shortname
			$newURL=$cmsPath . $link;
			//header("Location: $newURL", true, 301);
			// close database file
			sqlite_close($handle);
			header("Location: $newURL");
			exit;
		}else{
			$showlanguages='0';
		}
	}else if($languagesByShortName[$languageShort]['id']==$savedShowlanguages){
			$newURL=$cmsPath . $link;
			// close database file
			sqlite_close($handle);
			//header("Location: $newURL", true, 301);
			header("Location: $newURL");
			exit;
	}else{
		$showlanguages=$languagesByShortName[$languageShort]['id'];
	}
	
}else{
	$showlanguages=dbNumberLanguages2Show($handle);
}

$languagesByShortName=dbLanguagesByShortName($handle);
$languagesByID=dbLanguagesByID($handle);

if($showlanguages!=NULL){//mostly in case it is called with a nonexisting language
		$articlesInDateRange=dbArticlesInDateRange($handle,$initDate,$endDate,$showlanguages);
		$categoriesList=dbCategoriesList($handle);
		$categoriesTree=dbCategoriesTree($handle,FALSE,$showlanguages);
}



//CONFIG DATA
$webTitle=dbWebTitle($handle);


// all done
// close database file
sqlite_close($handle);

?>