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
//TODO: si language es any, mostrar solo parents any?? ahora muestra todos

include_once("session.inc.php");

//check if magic quotes on, then stripslashes
magicQuotes();
$category=$_GET['category'];

//echo sqlite_libversion();

//CONFIG DATA
$webTitle=dbWebTitle($handle);

$categoriesData=dbCategoriesData($handle);
$parentCategoriesList=dbCategoriesList($handle,false);
$languagesList=dbLanguagesList($handle);

$categoriesList=dbCategoriesList($handle);

if(!is_null($category)){
	$category=sqlite_escape_string($category);
	$categoryData=dbCategoryData($handle,$category);
	$categoryChildren=dbCategoryChildren($handle,$category);
	$parentCategoriesList=dbCategoriesList($handle,false,$categoryData['languageID']);
}else{
	$parentCategoriesList=dbCategoriesList($handle,false,2);//2 is english, default language?
}


// all done
// close database file
dbClose($handle);

?>