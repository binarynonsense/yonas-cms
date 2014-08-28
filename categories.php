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

include_once("include/initcategory.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>

	<?=htmlTitle($webTitle,$categoryData["name"])?>

	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<?=htmlMetaStyle($cmsPath .$publicThemePath.'style.css')?>
	<?=htmlMetaAuthor('Alvaro Garcia C  -  www.binarynonsense.com')?>
	<?=htmlMetaDescription('Yonas CMS, a content management system based on php 5 and SQLite by Alvaro García')?>
	<?=htmlMetaKeywords('Alvaro, García, Cuesta,Yonas,CMS, content, management, system, php, sqlite')?>

</head>
<body>

<div id="content">

	<div id="header">
		<?=$webTitle?>
	</div>

	<div id="menu">

		<?=menu1($languageShort)?>

	</div>

	<div id="center">

		<div id="verticalmenu">
		
		<p>Categories:</p>
		
		<ul><!--begin list-->
		<?php
			$length=count($categoriesTree);
			for($i=0;$i<$length;$i++) {
				
				if($categoriesTree[$i]['childID']==NULL){
					// IS ROOT
					echo "<li>\n";
					echo "<a href='"  . $cmsPath;
					if($languageShort!=NULL){echo "$languageShort/";}
					echo "category/".string2Url($categoriesTree[$i]['rootName'])."-".$categoriesTree[$i]['rootID']."/'>".$categoriesTree[$i]['rootName']."</a>\n";
					echo "</li>\n";

				}else{
				
					if($lastRoot!=$categoriesTree[$i]['rootID']){
						//first parent-child row -> i need to print the parent and the the child
						$lastRoot=$categoriesTree[$i]['rootID'];
						echo "<li>\n";
						echo "<a href='"  . $cmsPath;
						if($languageShort!=NULL){echo "$languageShort/";}
						echo "category/".string2Url($categoriesTree[$i]['rootName'])."-".$categoriesTree[$i]['rootID']."/'>".$categoriesTree[$i]['rootName']."</a>\n";
						echo "</li>\n";
						echo "<li>\n";
						echo "<ul>\n";//it's root, begin children list
					}
					
					echo "<li>\n";
					echo "<a href='"  . $cmsPath;
					if($languageShort!=NULL){echo "$languageShort/";}
					echo "category/".string2Url($categoriesTree[$i]['childName'])."-".$categoriesTree[$i]['childID']."/'>".$categoriesTree[$i]['childName']."</a>\n";
					echo "</li>\n";
					if(($i+1)<$length){
						if($categoriesTree[$i]['rootID']!=$categoriesTree[$i+1]['rootID']){
							echo "</ul>\n";
							echo "</li>\n";
						}
					}else{
						echo "</ul>\n";
						echo "</li>\n";
					}
				}		
			}
		?>
		</ul> <!--end list-->
		
		<p>Languages:</p>
		
		<div id="flags">
			<p>
			<a href="<?php 
			echo $cmsPath;
			if($savedShowlanguages!=2){echo "en/";}
			?>"><img src="<?=$publicThemePath?>englishflag.jpg" alt="englishflag" /></a>
			<a href="<?php 
			echo $cmsPath;
			if($savedShowlanguages!=3){echo "es/";}
			?>"><img src="<?=$publicThemePath?>spanishflag.jpg" alt="spanishflag" /></a>
			<a href="<?php 
			echo $cmsPath;
			if($savedShowlanguages!=0){echo "all/";}
			?>"><img src="<?=$publicThemePath?>allflag.jpg" alt="all languages flag" /></a>				
			</p>
		</div> <!-- flags-->
		
		<p>Feeds:</p>
		
		<div id="rss">
			<a href="<?=$cmsPath?>rss.php"><img src="<?=$publicThemePath?>rss091.gif" alt="new articles" /></a>
		</div> <!-- rss-->
		
		</div> <!-- verticalmenu-->
			

		<div id="left">
		<?php
			echo "" . $categoryData['name'] . ":<br />";
			
			if(count($articlesCategory)>0){
				foreach ($articlesCategory as $key=>$value) {
					//echo $key;
					$articleData=dbArticleData($handle,$key);
					list($year, $month, $day, $hour) = string2Date($articleData['date']);
					echo "<p><a href='"  . $cmsPath;
					if($languageShort!=NULL){echo "$languageShort/";}
					echo "$year/$month/$day/" . string2Url($articleData['title']) . "-" . $articleData["ID"] .  "/'>" . $articleData['title'] . "</a> - $month/$day/$year</p>";
				}
				
			}else{
				echo "<p>this category is empty</p>";
			}
			
			/*  */
			$length=count($categoryChildren);
			if($length>0){
				
				echo "<h3>Subcategories</h3>";
			
				for($i=0;$i<$length;$i++) {
					$childCategoryData=dbCategoryData($handle,$categoryChildren[$i]);
					echo "" . $childCategoryData['name'] . ":<br />";
					$childArticlesCategory=dbArticlesCategory($handle,$categoryChildren[$i],'date',$showlanguages);
					if(count($childArticlesCategory)>0){
						foreach ($childArticlesCategory as $key=>$value) {
							//echo $key;
							$articleData=dbArticleData($handle,$key);
							list($year, $month, $day, $hour) = string2Date($articleData['date']);
							echo "<p><a href='"  . $cmsPath;
							if($languageShort!=NULL){echo "$languageShort/";}
							echo "$year/$month/$day/" . string2Url($articleData['title']) . "-" . $articleData["ID"] .  "/'>" . $articleData['title'] . "</a> - $month/$day/$year</p>";
						}
					}else{
						echo "<p>this subcategory is empty</p>";
					}
				}
				
			}	
			/*  */
			
			
			
sqlite_close($handle);

			?>
			
			<div id="footer">

				<p>Powered by: <a href="http://yonas.binarynonsense.com">Yonas CMS</a> - Design: <a href="http://www.binarynonsense.com">AGC</a></p>
				
			</div> <!-- footer -->
		
		</div> <!-- left-->

	</div> <!--  center -->

</div> <!--  content -->
<!-- Start of StatCounter Code -->
<script type="text/javascript">
var sc_project=3509105; 
var sc_invisible=1; 
var sc_partition=33; 
var sc_security="1cd6b552"; 
</script>

<script type="text/javascript" src="http://www.statcounter.com/counter/counter_xhtml.js"></script><noscript><div class="statcounter"><a class="statcounter" href="http://www.statcounter.com/free_hit_counter.html"><img class="statcounter" src="http://c34.statcounter.com/3509105/0/1cd6b552/1/" alt="free hit counter" /></a></div></noscript>
<!-- End of StatCounter Code -->
</body>
</html>