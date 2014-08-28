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


//functions related to database read/write operations
	
function dbOpen($path) {

	$handle=sqlite_open($path) or die("Could not open database");
	return $handle;
}

function dbClose($handle) {
	// all done
	// close database file
	sqlite_close($handle);
}

function dbWebTitle($handle) {
	$query = "SELECT value FROM configuration WHERE name='title'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
	if (sqlite_num_rows($result) > 0) {
		$row = sqlite_fetch_array($result);
		return utf8_decode ($row[0]);
	}
}

function dbNumber2Show($handle) {
	//returns the number of articles to show in each page
	$query = "SELECT value FROM configuration WHERE name='show'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
	if (sqlite_num_rows($result) > 0) {
	$row = sqlite_fetch_array($result);
	return $row[0];
	}
}

function dbNumberLanguages2Show($handle) {
	//returns the number of articles to show in each page
	$query = "SELECT value FROM configuration WHERE name='showlanguages'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
	if (sqlite_num_rows($result) > 0) {
	$row = sqlite_fetch_array($result);
	return $row[0];
	}
}

function dbNumberArticles($handle,$languages=0) {
	//returns the number of articles in the DB
	$query = "SELECT COUNT(*) FROM articles";
	if($languages!=0){
		$query .= " WHERE languageID=$languages";
	}
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
	if (sqlite_num_rows($result) > 0) {
	$row = sqlite_fetch_array($result);
	return $row[0];
	}
}

function dbNumberPages($handle,$languages=0) {
	//returns the number of articles in the DB
	$query = "SELECT COUNT(*) FROM pages";
	if($languages!=0){
		$query .= " WHERE languageID=$languages";
	}
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
	if (sqlite_num_rows($result) > 0) {
	$row = sqlite_fetch_array($result);
	return $row[0];
	}
}

function dbLanguagesList($handle){
	//
		$query = "SELECT ID,name FROM languages WHERE ID != '1' ORDER BY id";
		
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $languagesList[$row[0]]=utf8_decode ($row[1]);
		    }
		}
	return $languagesList;
}

function dbLanguagesByShortName($handle){
	//returns languages' data indexed by shortname
		//$query = "SELECT ID,name,shortname FROM languages WHERE ID != '1' ORDER BY id";
		$query = "SELECT ID,name,shortname FROM languages ORDER BY id";
		
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $languagesByShortName[$row[2]]['id']=utf8_decode ($row[0]);
			   $languagesByShortName[$row[2]]['name']=utf8_decode ($row[1]);
		    }
		}
	return $languagesByShortName;
}

function dbLanguagesByID($handle){
	//returns languages' data indexed by id
		//$query = "SELECT ID,name,shortname FROM languages WHERE ID != '1' ORDER BY id";
		$query = "SELECT ID,name,shortname FROM languages ORDER BY id";
		
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $languagesByID[$row[0]]['shortname']=utf8_decode ($row[2]);
			   $languagesByID[$row[0]]['name']=utf8_decode ($row[1]);
		    }
		}
	return $languagesByID;
}

function dbStatusList($handle){
	//
		$query = "SELECT ID,value FROM status ORDER BY id";
		
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $statusList[$row[0]]=utf8_decode ($row[1]);
		    }
		}
	return $statusList;
}

function dbPagesData($handle,$offset,$show,$onlyPublished=false,$languageID=0,$order='date'){
	//returns an array with the data from the PAGES in the DB 
	//limited by an offset($offset) and a maximun number of articles to extract($show)
	//if $onlyPublished is set to true it only returns those articles whose status is published
	$query = "SELECT * FROM pages";
	
	if($onlyPublished){
		$query .= " WHERE statusID='2'";
		if($languageID!=0){
		$query .= " AND languageID='$languageID'";
		}
	}elseif($languageID!=0){
		$query .= " WHERE languageID='$languageID'";
	}
	
	if ($order=='date_till_now'){
	
		$dateNow=date('YmdHis');
		$query .= " AND date <= '$dateNow' ORDER BY date DESC LIMIT $offset,$show";
	
	}else{
	
		$query .= " ORDER BY $order DESC LIMIT $offset,$show";
	}
	
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    $i=0;
		    while($row = sqlite_fetch_array($result)) {
			
				$pagesData[$i]["ID"]=utf8_decode ($row[0]);
				$pagesData[$i]["authorID"]=utf8_decode ($row[1]);
				$pagesData[$i]["languageID"]=utf8_decode ($row[2]);
				$pagesData[$i]["title"]=utf8_decode ($row[3]);
				$pagesData[$i]["text"]=utf8_decode ($row[4]);
				$pagesData[$i]["statusID"]=utf8_decode ($row[5]);
				$pagesData[$i]["date"]=utf8_decode ($row[6]);
				
				$i++;

		    }
			return $pagesData;

		}
}

function dbArticlesData($handle,$offset,$show,$onlyPublished=false,$full=true,$languageID=0,$order='date'){
	//returns an array with the data from the ARTICLES in the DB 
	//limited by an offset($offset) and a maximun number of articles to extract($show)
	//if $onlyPublished is set to true it only returns those articles whose status is published
	$query = "SELECT * FROM articles";
	
	if($onlyPublished){
		$query .= " WHERE statusID='2'";
		if($languageID!=0){
		$query .= " AND languageID='$languageID'";
		}
	}elseif($languageID!=0){
		$query .= " WHERE languageID='$languageID'";
	}
	
	if ($order=='date_till_now'){
	
		$dateNow=date('YmdHis');
		$query .= " AND date <= '$dateNow' ORDER BY date DESC LIMIT $offset,$show";
	
	}else{
	
		$query .= " ORDER BY $order DESC LIMIT $offset,$show";
	}
	
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    $i=0;
		    while($row = sqlite_fetch_array($result)) {
			
				$articlesData[$i]["ID"]=utf8_decode ($row[0]);
				$articlesData[$i]["authorID"]=utf8_decode ($row[1]);
				$articlesData[$i]["languageID"]=utf8_decode ($row[2]);
				$articlesData[$i]["title"]=utf8_decode ($row[3]);
				$articlesData[$i]["subtitle"]=utf8_decode ($row[4]);
				$articlesData[$i]["prelude"]=utf8_decode ($row[5]);
				//$articlesData[$i]["text"]=utf8_decode ($row[6]);
				$articlesData[$i]["statusID"]=utf8_decode ($row[7]);
				$articlesData[$i]["date"]=utf8_decode ($row[8]);
				
				if($full){
					$articlesData[$i]["text"]=utf8_decode ($row[6]);
				}else{
					$splittedText = explode("<!-- more -->", utf8_decode ($row[6]));
					$articlesData[$i]["text"]= $splittedText[0];
					if($splittedText[1]){
						$articlesData[$i]["more"]=true;
					}					
				}

				$i++;

		    }
			return $articlesData;

		}
}

function dbArticlesInDateRange($handle,$initDate,$endDate,$languageID=0,$onlyPublished=true){
	//returns an array with the data from the ARTICLES in the DB 
	//which date is in the range [$initDate,$endDate]
	//if $onlyPublished is set to true it only returns those articles whose status is published
	$query = "SELECT ID,authorID,languageID,title,statusID,date FROM articles WHERE date>=$initDate AND date<=$endDate";
	
	if($onlyPublished){
		$query .= " AND statusID='2'";
	}
	if($languageID!=0){
		$query .= " AND languageID='$languageID'";
		}
	$query .= " ORDER BY date DESC";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    $i=0;
		    while($row = sqlite_fetch_array($result)) {
			
				$articlesInDateRange[$i]["ID"]=utf8_decode ($row[0]);
				$articlesInDateRange[$i]["authorID"]=utf8_decode ($row[1]);
				$articlesInDateRange[$i]["languageID"]=utf8_decode ($row[2]);
				$articlesInDateRange[$i]["title"]=utf8_decode ($row[3]);
				$articlesInDateRange[$i]["statusID"]=utf8_decode ($row[4]);
				$articlesInDateRange[$i]["date"]=utf8_decode ($row[5]);

				$i++;

		    }
			return $articlesInDateRange;

		}
}

function dbArticleData($handle,$id){
	//returns an array with the data from the article with id=$id in the DB 
	//obtain data from DB
	$query = "SELECT * FROM articles WHERE ID='$id'";

	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));

	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
			
				$articleData["ID"]=utf8_decode ($row[0]);
				$articleData["authorID"]=utf8_decode ($row[1]);
				$articleData["languageID"]=utf8_decode ($row[2]);
				$articleData["title"]=htmlspecialchars(utf8_decode ($row[3]),ENT_QUOTES);
				$articleData["subtitle"]=utf8_decode ($row[4]);
				$articleData["prelude"]=utf8_decode ($row[5]);
				$articleData["text"]=utf8_decode ($row[6]);
				$articleData["statusID"]=utf8_decode ($row[7]);
				$articleData["date"]=utf8_decode ($row[8]);
		    }
			return $articleData;

		}

}

function dbPageData($handle,$id){
	//returns an array with the data from the page with id=$id in the DB 
	//obtain data from DB
	$query = "SELECT * FROM pages WHERE ID='$id'";

	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));

	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
			
				$pageData["ID"]=utf8_decode ($row[0]);
				$pageData["authorID"]=utf8_decode ($row[1]);
				$pageData["languageID"]=utf8_decode ($row[2]);
				$pageData["title"]=htmlspecialchars(utf8_decode ($row[3]),ENT_QUOTES);
				$pageData["text"]=utf8_decode ($row[4]);
				$pageData["statusID"]=utf8_decode ($row[5]);
				$pageData["date"]=utf8_decode ($row[6]);
		    }
			return $pageData;

		}

}

function dbCategoriesData($handle){
	//returns an array with the data from the CATEGORIES in the DB 
	$query = "SELECT * FROM categories ORDER BY parentID,name";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    $i=0;
		    while($row = sqlite_fetch_array($result)) {
			
				$categoriesData[$i]["ID"]=utf8_decode ($row[0]);
				$categoriesData[$i]["parentID"]=utf8_decode ($row[1]);
				$categoriesData[$i]["languageID"]=utf8_decode ($row[2]);
				$categoriesData[$i]["name"]=utf8_decode ($row[3]);
				$i++;

		    }
			return $categoriesData;

		}
}

function dbCategoryData($handle,$id){
	//returns an array with the data from the CATEGORIES in the DB 
	$query = "SELECT * FROM categories WHERE ID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    $row = sqlite_fetch_array($result);
			
				$categoryData["ID"]=utf8_decode ($row[0]);
				$categoryData["parentID"]=utf8_decode ($row[1]);
				$categoryData["languageID"]=utf8_decode ($row[2]);
				$categoryData["name"]=utf8_decode ($row[3]);
				

		    
			return $categoryData;

		}
}

function dbCategoryChildren($handle,$id){
	//returns an array of category ids with parent $id
	$query = "SELECT ID FROM categories WHERE parentID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    $i=0;
		    while($row = sqlite_fetch_array($result)) {

				$categoryChildren[$i]=$row[0];
				$i++;

		    }
			return $categoryChildren;


		}		
}
function dbSetCategoryParent($handle,$id,$parent=0){
	$query = "UPDATE categories SET parentID='$parent' WHERE ID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	//echo "category $id now is root\n";
}

function dbCategoriesList($handle,$all=true,$languages=0){
	//returns an array of all the CATEGORIES IDs in the DB
	//if all=false it only returns the root categories (parent=0)
	//language 1 is the language of the default category, which must always be returned
	
	if ($all==true){
		$query = "SELECT ID,name FROM categories";
		if($languages!=0){
			$query.=" WHERE languageID='$languages' OR languageID='1'";
		}
	}else{
		$query = "SELECT ID,name FROM categories WHERE parentID=0";
		if($languages!=0){
			$query.=" AND languageID='$languages' OR languageID='1'";
		}
	}
	$query.=" ORDER BY name";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $categoriesList[$row[0]]=utf8_decode ($row[1]);
			   //echo "$row[0]+$row[3]";
		    }
		}
	return $categoriesList;
}

function dbCategoriesTree($handle,$count=TRUE, $languages=0){
	//returns an array with all the neccessary CATEGORIES' data to create a TREE
	//language 1 is the language of the default category, which must always be returned
		
		$query = "SELECT cat1.ID,cat1.name,cat2.ID,cat2.name,cat2.languageID FROM categories as cat1 LEFT JOIN categories as cat2 ON cat2.parentID=cat1.ID WHERE cat1.parentID=0";
		if($languages!=0){
			$query.=" AND cat1.languageID=$languages OR cat1.languageID='1'";
		}
		$query.=" ORDER BY cat1.name,cat2.name";

	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
			$i=0;
			//$previousRoot=NULL;
		    while($row = sqlite_fetch_array($result)) {
				
				/*if($row[4]==$languages||$languages==1){
					$categoriesTree[$i]['rootID']=$row[0];
					$categoriesTree[$i]['rootName']=utf8_decode ($row[1]);
					$categoriesTree[$i]['childID']=$row[2];
					$categoriesTree[$i]['childName']=utf8_decode ($row[3]);
					$previousRoot=$row[0];
				}elseif($previousRoot!=$row[0]){
					$categoriesTree[$i]['rootID']=$row[0];
					$categoriesTree[$i]['rootName']=utf8_decode ($row[1]);
					$previousRoot=$row[0];
				}*/
				$categoriesTree[$i]['rootID']=$row[0];
				$categoriesTree[$i]['rootName']=utf8_decode ($row[1]);
				$categoriesTree[$i]['childID']=$row[2];
				$categoriesTree[$i]['childName']=utf8_decode ($row[3]);
				if($count){
					if($categoriesTree[$i]['childID']==NULL){
						$categoriesTree[$i]['number']=dbArticlesCategoryCount($handle,$categoriesTree[$i]['rootID']);
					}else{
						$categoriesTree[$i]['number']=dbArticlesCategoryCount($handle,$categoriesTree[$i]['childID']);
					}
				}
				
				$i++;
		    }
		}
	return $categoriesTree;
}

function dbGroupsList($handle){
	//returns an array of all the USERS IDs in the DB
	$query = "SELECT ID,name FROM groups ORDER BY ID";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $groupsList[$row[0]]=$row[1];
		    }
		}
	return $groupsList;
}

function dbUsersList($handle){
	//returns an array of all the USERS IDs in the DB
	$query = "SELECT ID,username FROM users ORDER BY ID";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $usersList[$row[0]]=utf8_decode ($row[1]);
		    }
		}
	return $usersList;
}

function dbUsersListFullName($handle){
	//returns an array of all the USERS IDs in the DB
	$query = "SELECT ID,name FROM users ORDER BY ID";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $usersList[$row[0]]=utf8_decode ($row[1]);
		    }
		}
	return $usersList;
}

function dbUserData($handle,$id){
	//returns an array with the data from user with ID=$id
	//TODO: extract description (regenerate DB)
	$query = "SELECT ID,username,name,mail,groupID FROM users WHERE ID=$id";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $userData['ID']=$row[0];
			   $userData['username']=utf8_decode ($row[1]);
			   $userData['fullname']=utf8_decode ($row[2]);
			   $userData['mail']=utf8_decode ($row[3]);
			   $userData['groupID']=$row[4];
		    }
		}
	return $userData;
}

function dbUserLoginData($handle,$id){
	//returns an array with the login related data from user with ID=$id
	//TODO: extract description (regenerate DB)
	$query = "SELECT ID,username,hash,salt FROM users WHERE ID='$id'";
	
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));

		
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
		       $userLoginData['ID']=$row[0];
			   $userLoginData['username']=utf8_decode ($row[1]);
			   $userLoginData['hash']=$row[2];
			   $userLoginData['salt']=$row[3];
		    }
		}
	return $userLoginData;
}

function dbUserLoginDatabyName($handle,$userName){
	//returns an array with the login related data from user with ID=$id
	//TODO: extract description (regenerate DB)
	$query = "SELECT hash,salt FROM users WHERE username='$userName'";
	
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
		    while($row = sqlite_fetch_array($result)) {
			   $userLoginData['hash']=$row[0];
			   $userLoginData['salt']=$row[1];
		    }
		}
	return $userLoginData;
}

function dbDeleteArticle($handle,$id){
	//deletes the ARTICLE with id=$id from the articles table
	//obtain data from DB
	$query = "DELETE FROM articles WHERE ID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
}

function dbDeletePage($handle,$id){
	//deletes the ARTICLE with id=$id from the articles table
	//obtain data from DB
	$query = "DELETE FROM pages WHERE ID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
}

function dbDeleteArticleCategory($handle,$article,$category){
	//Given a categoryID and an articleID it deletes all the article-category relations where the 
	//articleID-categoryID relation is equal to the one formed by the IDs provided
	//obtain data from DB
	$query = "DELETE FROM article_category WHERE articleID='$article' AND categoryID='$category'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
}

function dbDeleteAllArticleCategory($handle,$category){
	//Given a categoryID it deletes all the article-category relations where the 
	//categoryID is equal to the ID provided
	//obtain data from DB
	$query = "DELETE FROM article_category WHERE categoryID='$category'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
}

function dbDeleteAllCategoryArticle($handle,$article){
	//Given an articleID it deletes all the article-category relations where the 
	//articleID is equal to the ID provided
	//obtain data from DB
	$query = "DELETE FROM article_category WHERE articleID='$article'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
}

function dbSaveArticleCategory($handle,$article,$category){
	//Given a categoryID and an articleID it SAVES the article-category relation 
	//formed by the IDs provided
	//obtain data from DB
	$query = "INSERT INTO article_category (articleID,categoryID) VALUES ('$article','$category')";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
}

function dbArticleCategories($handle,$id){
	//Returns an array with the IDs of the categories assigned to an article given by its ID
	$query = "SELECT categoryID FROM article_category WHERE articleID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
			$i=0;
		    while($row = sqlite_fetch_array($result,SQLITE_NUM)){
				$articleCategories[$i]=$row[0];
				$i++;
			}
		}else{//echo "no rows";
		}
	
	//$articleCategories=sqlite_array_query($handle,$query,SQLITE_ASSOC);
	return $articleCategories;
}


function dbArticlesCategory($handle,$id,$order='date',$languages=0){
	//Returns an array with the IDs of the articles assigned to a category given by its ID
	//$query = "SELECT articleID FROM article_category WHERE categoryID='$id'";
	//$query = "SELECT articleID,date FROM article_category LEFT JOIN articles ON articleID=ID WHERE categoryID='$id' ORDER BY $order DESC";
	$query = "SELECT articleID,date FROM article_category LEFT JOIN articles ON articleID=ID WHERE categoryID='$id' ";
	if($languages!=0){
		$query.="AND languageID='$languages' ";
	}
	$query.="ORDER BY $order DESC";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		// if rows exist
		if (sqlite_num_rows($result) > 0) {
		    // get each row as an array
			$i=0;
		    while($row = sqlite_fetch_array($result,SQLITE_NUM)){
				$articlesCategory[$row[0]]=true;
				$i++;
			}
		}
	return $articlesCategory;
}

function dbArticlesCategoryCount($handle,$id){
	//Returns the number of articles assigned to a category given by its ID
	$query = "SELECT COUNT(*) FROM article_category WHERE categoryID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
		
		// if rows exist
	$row = sqlite_fetch_array($result,SQLITE_NUM);
	return $row[0];
}

function dbDeleteCategory($handle,$id){
	//deletes the CATEGOY with id=$id from the categories table
	$query = "DELETE FROM categories WHERE ID='$id'";
	// execute query
	$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
	//echo "$result article-category deleted<br>";
}

?>