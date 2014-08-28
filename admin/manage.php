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

include_once("../include/adminmanage.inc.php");
include_once("../include/formatting.inc.php");
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
			<a href='managecategories.php' >Categories</a>
			<a href='managepages.php' >Pages</a>
			<a href='manage.php' id='tabselected'>Articles</a>
		</div><!-- mainheader div -->

		<div id="writeform">
			<!-- TABLE MANAGE -->
			<table class="tableStyle colorScheme">
				<thead>
					<tr>
						<th scope="col">Title</th>
						<th scope="col">Author</th>
						<th scope="col">Language</th>
						<th scope="col">Date</th>
						<th scope="col">Status</th>
						<th scope="col">Edit</th>
						<th scope="col">Delete</th>
					</tr>
				</thead>
			<tbody>
			<?php
					
			//ARTICLES


			    for($i=0;$i<count($articlesData);$i++) {

					if($i%2==0){
						echo "<tr class='even'>\n";
					}else{
						echo "<tr class='odd'>\n";
					}
					echo "<td>".$articlesData[$i]["title"]."</td>\n";//title
					echo "<td>".$usersList[$articlesData[$i]["authorID"]]."</td>\n";//authorid
					echo "<td>".$languagesList[$articlesData[$i]["languageID"]]."</td>\n";//languageid
					list($year, $month, $day, $hour) = string2Date($articlesData[$i]["date"]);
					echo "<td>".$month."/".$day."/".$year."</td>\n";//date
					echo "<td>".$statusList[$articlesData[$i]["statusID"]]."</td>\n";
					echo "<td><a href='article.php?article=" . $articlesData[$i]["ID"] ."'>Edit</a></td>\n";
					echo "<td><a href='delete.php?type=article&id=" . $articlesData[$i]["ID"] ."'>Delete</a></td>\n";
					echo "</tr>\n";
					

			    }


			?>
			</tbody>
			</table>
			<!-- END TABLE MANAGE -->

			<?php
			//Calculate offset for previous and next pages
			$newOffset=$offset+$show;
			$oldOffset=$offset-$show;
			if ($oldOffset>=0){
				echo "<a href='manage.php?offset=$oldOffset'>newer Posts</a> ";
			}
			if ($numberArticles-$newOffset>0){
				echo "<a href='manage.php?offset=$newOffset'>older Posts</a>";
			}

			?>

		</div><!-- writeform div -->

	</div><!-- main div -->

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->


</div><!-- containerfull div -->

<?php PrintFooter(); ?>

</body>
</html>
