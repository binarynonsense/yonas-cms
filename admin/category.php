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


include_once("../include/admin.inc.php");
include_once("../include/dbfunctions.inc.php");//extract data from db
include_once("../include/admincategory.inc.php");

?>

<html>

<title>Create / Edit Category - Admin Area</title>
 
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" href="theme/admin.css" type="text/css" media="screen" />

<script language="javascript" type="text/javascript" src="js/ajax.js"></script>

</head>

<body <?php

if(is_null($category)){
	echo "onload='document.mainform.name.focus( );document.mainform.name.select( )'";
} 
?>>

<?php 
PrintHeader($webTitle); 
PrintMenu1(1);
?>




<div id="containerfull">

	<form method="post" action="save.php" name="mainform">

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->
	<div id="mainfull">

		<div id="mainheader">
			<a href='./language.php' >Language</a>
			<a href='./user.php' >User</a>
			<a href='./category.php' id='tabselected'>Category</a>
			<a href='./page.php' >Page</a>
			<a href='./article.php'>Article</a>
		</div><!-- mainheader div -->

		<div id="writeform">

			<input type='hidden' name='type' value='category'>
			
			<p class="settingslabel">Name: <a href="#">[?]<span>The name of the category must be unique</span></a></p>
			<input class="settingstext" type="text" name="name" size="20" <?php
					 if(!is_null($category)){
						echo "value='".$categoryData['name']."'";
					 }else{
						echo "value='Insert Name'";
					 }

			?>>
			
			<?php
					if(!is_null($category)){
						echo "<input type='hidden' name='id' value='$category'>";
						echo "<input type='hidden' name='oldlanguageid' value='".$categoryData['languageID']."'>";
					}
			?>
			
			<?php 
			if($category!=1){//if category is not default
			?>
				<p class="settingslabel">Parent Category:</p>
				
				<div id="parentdiv">
				<select name="parent">

					<option value='0' 'selected'>No parent</option>
					
					<?php/*
				
					foreach ($parentCategoriesList as $key => $value){
						echo "<option value='$key' ";
						if($key==$categoryData['parentID']) echo "selected";
						echo ">$value</option>";
					}*/

					?>
					<?php	
					
					if(is_null($categoryChildren)){	//don't let a parent change to children if it has children of its own
						foreach ($parentCategoriesList as $key => $value){
							if($key!=$category && $key != 1){
								echo "<option value='$key' ";
								if($key==$categoryData['parentID']) echo "selected";
								echo ">$value</option>";
							}
						}
					}

					?>
					
				</select>
				</div>
				
							
				<p class="settingslabel">Language:</p>
				<select name="languageid" onchange ="getData('ajaxcategory.php?language='+this.value<?php 
						if($category!=NULL){echo "+'&category=$category'";} ?>, 'parentdiv')">
				
					<?php
				
					foreach ($languagesList as $key => $value){

					echo "<option value='$key' ";
					if($key==$categoryData['languageID']) echo "selected";
						echo ">$value</option>";
					}

					?>
				</select>
					<?php
					if(!is_null($category)){
					?>
				<p>Note: if you change the language of the category all the articles stored under it will no longer be under this category and will be placed under de default category if they aren't under any other.</p>
					<?php
					}
					?>
			<?php 
			}//end if category is not default
			?>
			
			<p id="formbuttons">
				<input class="formbutton" type="submit" name="savecategory" value="Save Category">
			</p>
			
			</p>
			 
			 
		</div><!-- writeform div -->

		</div><!-- main div -->

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->


	</form>
</div><!-- containerfull div -->

<?php PrintFooter(); ?>

</body>
</html>
