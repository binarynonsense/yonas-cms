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

include_once("../include/session.inc.php");

//check if magic quotes on, then stripslashes
magicQuotes();

$name=sqlite_escape_string($_GET['id']);
$type=$_GET['type'];

if($type=="article"){

	dbDeleteArticle($handle,$id);
	dbDeleteAllCategoryArticle($handle,$id);
	//echo "article deleted";
	alertBox('ok','Article successfully deleted','To go back click ',$_SERVER['HTTP_REFERER']);
	
}elseif($type=="category"){

	//TODO: make the operation atomic???? may be potential problems if not

	if ($id==1){
		alertBox('error','ERROR','DEFAULT category, ID = 1, can NOT be deleted<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	// set path of database file
	$dbPath = "yonas.db";

	$handle=dbOpen($dbPath);
	//categoryID-->list of articleID
	$articlesCategory=dbArticlesCategory($handle,$id);//ej $articlesCategory[1]=true...
	//print_r ($articlesCategory);

	dbDeleteAllArticleCategory($handle,$id);//delete all article-category relations
	
	//any article without any category?-->assign it the default category
	if(isset($articlesCategory)){
		foreach($articlesCategory as $key => $value){
			if(is_null(dbArticleCategories($handle,$key))){
				dbSaveArticleCategory($handle,$key,1);
			}
		}
	}
	
	//any category whose parent was this?-->make them root categories	
	if($categoryChildren=dbCategoryChildren($handle,$id)){
		foreach($categoryChildren as $children){
			dbSetCategoryParent($handle,$children);//makes parent=root by default		
		}
	}
	
	
	dbDeleteCategory($handle,$id);//delete the category
	//echo "\ncategory deleted!!\n";
	alertBox('ok','Category successfully deleted','To go back click ',$_SERVER['HTTP_REFERER']);
	
}elseif($type=="page"){

	dbDeletePage($handle,$id);
	alertBox('ok','Page successfully deleted','To go back click ',$_SERVER['HTTP_REFERER']);
	
}

// all done
// close database file
dbClose($handle);
?>