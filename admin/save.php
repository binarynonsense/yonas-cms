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

//This file receives the neccesary info from create.php to save an article or category or... in the database
//TODO: don't let default category be children

include_once("../include/session.inc.php");

//$name=sqlite_escape_string($_POST['id']);echo $name;
if (isset($_POST['type'])){
		$type=sqlite_escape_string($_POST['type']);
	}else if(isset($_GET['type'])){
		$type=sqlite_escape_string($_GET['type']);
	}


if($type=="article"){

	if ($_POST["categories"]!=NULL){
		$articleCategories=$_POST["categories"];
	}else{//ERROR
		alertBox('error','ERROR','Article must be included at least in <b>one</b> category.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	
	$oldArticleCategories=$_POST["oldcategories"];
	
	//check if magic quotes on, then stripslashes
	magicQuotes();

	if ($_POST['id']!=NULL){
		$article=sqlite_escape_string($_POST['id']);
	}

	
	
	//$mail=sqlite_escape_string($_POST['mail']);	
	//$status=$_POST['status'];
	$text=sqlite_escape_string(utf8_encode ($_POST['text']));
	//$date=sqlite_escape_string($_POST['date']);
	$publish=sqlite_escape_string($_POST['publish']);
	$draft=sqlite_escape_string($_POST['draft']);
	
	if ($_POST['title']!=NULL){
		$title=sqlite_escape_string(utf8_encode ($_POST['title']));
		//echo "name=".$name;
	}else{//ERROR
		alertBox('error','ERROR','You must supply a <b>title</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	if ($_POST['language']!=NULL){
		$languageID=sqlite_escape_string($_POST['language']);
	}else{//ERROR
		alertBox('error','ERROR','You must supply a <b>language</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	if ($_POST['author']!=NULL){
		$author=sqlite_escape_string($_POST['author']);
	}else{//ERROR
		alertBox('error','ERROR','You must supply an <b>author</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	
	if ($_POST['year']!=NULL){
		$year=sqlite_escape_string($_POST['year']);
		$month=sqlite_escape_string($_POST['month']);
		$day=sqlite_escape_string($_POST['day']);
		$hour=sqlite_escape_string($_POST['hour']);
		$minute=sqlite_escape_string($_POST['minute']);
		$second=sqlite_escape_string($_POST['second']);
		
		$date=$year.$month.$day.$hour.$minute.$second;
	}


	if($publish!=NULL){
	//echo "published=$publish";
		$status="2";//published
	}
	if($draft!=NULL){
	//echo "draft=$draft";
		$status="1";//draft
	}

			// set path of database file
			$db = "yonas.db";
			// open database file
			$handle = sqlite_open($db) or die("Could not open database");

			
	if(!isset($article)){
	/////////////////////////////////////////////////////////////////////////////
		//NEW
		$date=date('YmdHis');
		sqlite_query($handle, "INSERT INTO articles (authorID,languageID,title,subtitle,prelude,text,statusID,date) VALUES ('$author','$languageID','$title',NULL,NULL,'$text','$status', '$date')") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		$id=sqlite_last_insert_rowid($handle);

		//echo "NEW - id=$id\n";
		$message.="Article ID: ".$id."<br />";
			
		foreach($articleCategories as $new){
		//echo $id . "-" . $new;
			dbSaveArticleCategory($handle,$id,$new);
			$message.="Added to category number: ".$new."<br />";
		}
		//$_SERVER['HTTP_REFERER']
		$url=$cmsPath."article.php?article=".$id;
		alertBox('ok','Article Successfully Created',$message.'.<br />To go back click ',$url);
	///////////////////////////////////////////////////////////////////////////////
	}else{//UPDATE ARTICLE
	/////////////////////////////////////////////////////////////////////////////
		
		sqlite_query($handle, "UPDATE articles SET authorID='$author',title='$title',text='$text', date='$date', languageID='$languageID', statusID='$status' WHERE ID='$article'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		$message.='Title: <b>'.utf8_decode($title).'</b><br />';
		
		//look for categories previously stored but no longer selected in order to delete them
			foreach($oldArticleCategories as $old){
				$delete=true;
				foreach($articleCategories as $new){
					if($old==$new){
						$delete=false;
					}
				}
				if($delete){
					dbDeleteArticleCategory($handle,$article,$old);
					$message.='Article deleted from category number <b>'.$old.'</b><br />';
				}
			}
		//look for categories previously not stored but now selected in order to save them	
			foreach($articleCategories as $new){
				$save=true;
				foreach($oldArticleCategories as $old){
					if($new==$old){
						$save=false;
					}			
				}
				if($save){
					dbSaveArticleCategory($handle,$article,$new);
					$message.='Article added to category number <b>'.$new.'</b><br />';
				}
			}
		
		alertBox('ok','Article Successfully Updated',$message.'.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	/////////////////////////////////////////////////////////////////////////////
	}
///////////////////////////////////////////////////////////////////////////////////////////	
}elseif($type=="category"){//end if article

	// Is magic quotes on? 
	if (get_magic_quotes_gpc()) { // Yes? Strip the added slashes 
	$_REQUEST = array_map('stripslashes', $_REQUEST); $_GET = array_map('stripslashes', $_GET); $_POST = array_map('stripslashes', $_POST); $_COOKIE = array_map('stripslashes', $_COOKIE); }

	if ($_POST['id']!=NULL){
		$category=sqlite_escape_string($_POST['id']);
	}

	if($category==NULL){
		//CREATE NEW CATEGORY/////////////////////////////////////////////
		if ($_POST['name']!=NULL){
			$name=sqlite_escape_string(utf8_encode($_POST['name']));
			//echo "name=".$name;
			$message.="Category name: <b>".utf8_decode($name)."</b><br />";
		}else{//ERROR
			alertBox('error','ERROR','You must supply a <b>name</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
		}
		if ($_POST['languageid']!=NULL){
			$languageID=sqlite_escape_string($_POST['languageid']);
		}else{//ERROR
			alertBox('error','ERROR','You must supply a <b>languageID</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
		}
		if ($_POST['parent']!=NULL){
			$parentID=sqlite_escape_string($_POST['parent']);
		}else{
			$parent=0;
		}
		
		// set path of database file
		$dbPath = "yonas.db";
		// open database file
		$handle = sqlite_open($dbPath) or die("Could not open database");
		
		//does the name already exists?
		$nameUpper=strtoupper($name);
		$result=sqlite_query($handle, "SELECT COUNT(*) FROM categories WHERE upper(name)='$nameUpper'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		$row = sqlite_fetch_array($result);
		}
		if($row[0]==0){
			//create
			sqlite_query($handle, "INSERT INTO categories (name,parentID,languageID) VALUES ('$name','$parentID','$languageID')") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
			//echo "category $name with parent: $parentID created<br>";
			$message.="Parent ID: $parentID.<br />";
			alertBox('ok','Category successfully created',$message.'.<br />To go back click ',$_SERVER['HTTP_REFERER']);
		}else{//ERROR
			alertBox('error','ERROR','There\'s already a category named <b>'.$nameUpper.'</b><br />To go back click ',$_SERVER['HTTP_REFERER']);
		}
		
		
	}else{//EDIT CATEGORY/////////////////////////////////////////////
	
	/////////////////////////////////////////////////////////////////	

		if ($_POST['name']!=NULL){
			$name=sqlite_escape_string(utf8_encode ($_POST['name']));
			$message.="Category name: <b>".utf8_decode($name)."</b><br />";
		}else{
			alertBox('error','ERROR','You must supply a <b>name</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
		}
		if ($_POST['languageid']!=NULL){
			$languageID=sqlite_escape_string($_POST['languageid']);
		}else{
			alertBox('error','ERROR','You must supply a <b>languageID</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
		}
		if ($_POST['oldlanguageid']!=NULL){
			$oldLanguageID=sqlite_escape_string($_POST['oldlanguageid']);
		}else{
			alertBox('error','ERROR','Where\'s the old <b>languageID</b>??.<br />To go back click ',$_SERVER['HTTP_REFERER']);
		}
		if ($_POST['parent']!=NULL){
			$parentID=sqlite_escape_string($_POST['parent']);
		}else{
			$parent=0;
		}

		// set path of database file
		$dbPath = "yonas.db";
		// open database file
		$handle = sqlite_open($dbPath) or die("Could not open database");
		
		$categoryData=dbCategoryData($handle,$category);
		$nameUpper=strtoupper($name);
		//has the name changed?
		$result=sqlite_query($handle, "SELECT name FROM categories WHERE ID='$category'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		$row = sqlite_fetch_array($result);
		}
		if(strtoupper($row[0])==$nameUpper){
			//echo "name hasn't changed";
		}else{//NEW NAME
			//does the new name already exists?
			$result=sqlite_query($handle, "SELECT COUNT(*) FROM categories WHERE upper(name)='$nameUpper'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
			// if rows exist
			if (sqlite_num_rows($result) > 0) {
			$row = sqlite_fetch_array($result);
			}
			if($row[0]!=0){//ERROR
				alertBox('error','ERROR','There\'s already a category named <b>'.$nameUpper.'</b><br />To go back click ',$_SERVER['HTTP_REFERER']);
			}
		}
				
		//UPDATE////
		if($languageID!=$oldLanguageID){//language has changed -> move articles to default if they don't have another category
			if($category==1){
				alertBox('error','ERROR','DEFAULT category\'s language CAN NOT be changed<br />To go back click ',$_SERVER['HTTP_REFERER']);
			}
			//categoryID-->list of articleID
			$articlesCategory=dbArticlesCategory($handle,$category);//ej $articlesCategory[1]=true...
			dbDeleteAllArticleCategory($handle,$category);//delete all article-category relations
	
			//any article without any category?-->assign it the default category
			if(isset($articlesCategory)){
				foreach($articlesCategory as $key => $value){
					if(is_null(dbArticleCategories($handle,$key))){
						dbSaveArticleCategory($handle,$key,1);
					}
				}
			}
	
			//any category whose parent was this?-->make them root categories	
			if($categoryChildren=dbCategoryChildren($handle,$category)){
				foreach($categoryChildren as $children){
					dbSetCategoryParent($handle,$children);//makes parent=root by default		
				}
			}
		}//end if language not old
		sqlite_query($handle, "UPDATE categories SET name='$name',parentID='$parentID',languageID='$languageID' WHERE ID='$category'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
			
			//sqlite_query($handle, "INSERT INTO categories (name,parentID,languageID) VALUES ('$name','$parentID','$languageID')") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		//echo "category $name with parent: $parentID edited<br>";
		$message.="Parent ID: $parentID.<br />";
		alertBox('ok','Category successfully updated',$message.'.<br />To go back click ',$_SERVER['HTTP_REFERER']);
		
	//////////////////////////////////////////////////////////////////
	}//end edit category

///////////////////////////////////////////////////////////////////////////////////////////	
}elseif($type=="page"){//end if category

	
	//check if magic quotes on, then stripslashes
	magicQuotes();

	if ($_POST['id']!=NULL){
		$page=sqlite_escape_string($_POST['id']);
	}

	$text=sqlite_escape_string(utf8_encode ($_POST['text']));
	$publish=sqlite_escape_string($_POST['publish']);
	$draft=sqlite_escape_string($_POST['draft']);
	
	if ($_POST['title']!=NULL){
		$title=sqlite_escape_string(utf8_encode ($_POST['title']));
	}else{//ERROR
		alertBox('error','ERROR','You must supply a <b>title</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	if ($_POST['language']!=NULL){
		$languageID=sqlite_escape_string($_POST['language']);
	}else{//ERROR
		alertBox('error','ERROR','You must supply a <b>language</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	if ($_POST['author']!=NULL){
		$author=sqlite_escape_string($_POST['author']);
	}else{//ERROR
		alertBox('error','ERROR','You must supply an <b>author</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}
	
	if ($_POST['year']!=NULL){
		$year=sqlite_escape_string($_POST['year']);
		$month=sqlite_escape_string($_POST['month']);
		$day=sqlite_escape_string($_POST['day']);
		$hour=sqlite_escape_string($_POST['hour']);
		$minute=sqlite_escape_string($_POST['minute']);
		$second=sqlite_escape_string($_POST['second']);
		
		$date=$year.$month.$day.$hour.$minute.$second;
	}


	if($publish!=NULL){
		$status="2";//published
	}
	if($draft!=NULL){
		$status="1";//draft
	}

	// set path of database file
	$db = "yonas.db";
	// open database file
	$handle = sqlite_open($db) or die("Could not open database");

			
	if(!isset($page)){
	/////////////////////////////////////////////////////////////////////////////
		//NEW PAGE
		$date=date('YmdHis');
		sqlite_query($handle, "INSERT INTO pages (authorID,languageID,title,text,statusID,date) VALUES ('$author','$languageID','$title','$text','$status', '$date')") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		$id=sqlite_last_insert_rowid($handle);

		$message.="Page ID: ".$id."<br />";
			
		//$_SERVER['HTTP_REFERER']
		$url=$cmsPath."page.php?page=".$id;
		alertBox('ok','Page Successfully Created',$message.'.<br />To go back click ',$url);
	///////////////////////////////////////////////////////////////////////////////
	}else{//UPDATE PAGE
	/////////////////////////////////////////////////////////////////////////////
		
		sqlite_query($handle, "UPDATE pages SET authorID='$author',title='$title',text='$text', date='$date', languageID='$languageID', statusID='$status' WHERE ID='$page'") or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		$message.='Title: <b>'.utf8_decode($title).'</b><br />';
		
		alertBox('ok','Page Successfully Updated',$message.'.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	}//end if page
}
///////////////////////////////////////////////////////////////////////////////////////////	
// all done
// close database file
dbClose($handle);
?>