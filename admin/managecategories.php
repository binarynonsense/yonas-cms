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

//init data
include_once("../include/admin.inc.php");
include_once("../include/dbfunctions.inc.php");//extract data from db
include_once("../include/adminmanagecategories.inc.php");
?>



<html>

<title>Manage Articles - Admin Area</title>
 
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<link rel="stylesheet" href="theme/admin.css" type="text/css" media="screen" />
</head>

<body>

<?php 
PrintHeader($webTitle); 
PrintMenu1(2);
?>


<div id="containerfull">


	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->
	<div id="mainfull">

		<div id="mainheader">
			<a href='managelanguages.php'>Languages</a>
			<a href='managecategories.php' id='tabselected'>Categories</a>
			<a href='managepages.php' >Pages</a>
			<a href='manage.php' >Articles</a>
		</div><!-- mainheader div -->

		<div id="writeform">
			<!-- TABLE MANAGE -->
			<table class="tableStyle colorScheme">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Name</th>
						<th scope="col">Language</th>
						<th scope="col">Parent ID</th>
						<th scope="col">Edit</th>
						<th scope="col">Delete</th>
					</tr>
				</thead>
			<tbody>
			<?php
					
			//categories


			    for($i=0;$i<count($categoriesData);$i++) {

					if($i%2==0){
						echo "<tr class='even'>\n";
					}else{
						echo "<tr class='odd'>\n";
					}
					echo "<td>".$categoriesData[$i]["ID"]."</td>\n";//title
					echo "<td>".$categoriesData[$i]["name"]."</td>\n";//title
					echo "<td>".$languagesList[$categoriesData[$i]["languageID"]]."</td>\n";
					if($categoriesData[$i]["parentID"]==0){
						echo "<td>None</td>\n";
					}else{
						echo "<td>".$categoriesData[$i]["parentID"]."</td>\n";
					}
					echo "<td><a href='category.php?category=" . $categoriesData[$i]["ID"] ."'>Edit</a></td>\n";
					echo "<td><a href='delete.php?type=category&id=" . $categoriesData[$i]["ID"] ."'>Delete</a></td>\n";
					echo "</tr>\n";
					

			    }


			?>
			</tbody>
			</table>
			<!-- END TABLE MANAGE -->


		</div><!-- writeform div -->

	</div><!-- main div -->

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->

<?php /*
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->
	<div id="leftcolumn">

		<div id="lefttop">
		New Category
		</div><!-- righttop div -->

		<div id="left">
			<form method="post" action="save.php" name="mainform">

			<input type='hidden' name='type' value='category'>
			
			<p class="formlabelsmall">Name:</p>
			<input class="inputtextsmall" type="text" name="name" size="20" <?php
			 if(!is_null($article)){
				echo "value='".$categoryData["name"]."'";
			 }else{
				//echo "value='Insert Title'";
			 }

			?>>
			
			<p class="formlabelsmall">Parent Category:</p>
			<select name="parent">
			
				<option value='0' 'selected'>No parent</option>
				<?php
			
				foreach ($parentCategoriesList as $key => $value){

				echo "<option value='$key' ";
				//if($key==$author) echo "selected";
					echo ">$value</option>";
				}

				?>
			</select>
						
			<p class="formlabelsmall">Language:</p>
			<select name="languageid">
			
				<?php
			
				foreach ($languagesList as $key => $value){

				echo "<option value='$key' ";
				//if($key==$author) echo "selected";
					echo ">$value</option>";
				}

				?>
			</select>
			
			
			<p id="formbuttons">
				<input class="formbuttonsmall" type="submit" name="savecategory" value="Save">
			</p>
			
			</p>

			</form>
		</div><!-- right div -->

		
	</div><!-- rightcolumn div -->
	*/?>
	<!-- ------------------------------------------------------------------->

</div><!-- containerfull div -->

<?php PrintFooter(); ?>

</body>
</html>