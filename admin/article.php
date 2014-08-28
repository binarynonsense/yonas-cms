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

//this file allows the user to edit/create an article and 
//send the info to savearticle.php

include_once("../include/admin.inc.php");
include_once("../include/dbfunctions.inc.php");//extract data from db
include_once("../include/adminarticle.inc.php");

?>

<html>

<title>Edit / Create Article - Admin Area</title>
 
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" href="theme/admin.css" type="text/css" media="screen" />

<script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="js/tiny_mce/config_yonas.js"></script>
<script language="javascript" type="text/javascript" src="js/security.js"></script>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>
<script language="javascript" type="text/javascript" src="js/yonas_editor/editor.js"></script>
	
</head>

<body <?php

if(is_null($article)){
	echo "onload='document.mainform.title.focus( );document.mainform.title.select( )'";
} 
?>>


<?php 
PrintHeader($webTitle); 
PrintMenu1(1);
?>



<div id="containerfull">


	<div id="mainfull">

		<div id="mainheader">
			<a href='./language.php' >Language</a>
			<a href='./user.php' >User</a>
			<a href='./category.php' >Category</a>
			<a href='./page.php' >Page</a>
			<a href='./article.php' id='tabselected'>Article</a>
		</div><!-- mainheader div -->
		
		<div id="writeform">
		
			<form onsubmit="return validateArticle()" method="post" action="save.php" name="mainform" >

			<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->

				<div id="insideform">

					<p class="formlabel">Title:</p>
					<input class="inputtext" id="titletext" type="text" name="title" size="45" <?php
					 if(!is_null($article)){
						echo "value='".$articleData["title"]."'";
					 }else{
						echo "value='Insert Title'";
					 }

					?>></p>

					<?php
					if(!is_null($article)){
						echo "<input type='hidden' name='id' value='$article'>";
					}
					echo "<input type='hidden' name='type' value='article'>";
					?>

					<input type="hidden" name="status" value="<?php echo $articleData["statusID"]; ?>">
					
					<?php
					foreach($articleCategories as $articleCategory){
						echo "<input type='hidden' name='oldcategories[]' value='" . $articleCategory . "'>\n";
					}
					?>
					
					
					
					<p class="formlabel">Text Body:</p>

					<div class="sourcevisual">
						<a href="javascript:sourceEditor('text');" id="source">Source</a>
						<a href="javascript:visualEditor('text');" id="visual">Visual</a>
					</div>
					
					<div id="editor">
					
						<div id="editorbuttons">
							<a href="javascript:insertTags('text','<b>','</b>');"><img src="js/yonas_editor/img/bold.gif" alt="bold" title="bold"/></a>
							<a href="javascript:insertTags('text','<em>','</em>');"><img src="js/yonas_editor/img/italic.gif" alt="italic" title="italic" /></a>
							<a href="javascript:insertTags('text','<span style=\'text-decoration: underline;\'>','</span>');"><img src="js/yonas_editor/img/underline.gif" alt="underline" title="underline" /></a>
							<a href="javascript:insertTags('text','<span style=\'text-decoration: line-through;\'>','</span>');"><img src="js/yonas_editor/img/strike.gif" alt="strike" title="strike" /></a>
							<a href="javascript:insertTags('text','<p style=\'text-align: center;\'>','</p>');"><img src="js/yonas_editor/img/center.gif" alt="center" title="center" /></a>
							<a href="javascript:linkTag('text');"><img src="js/yonas_editor/img/link.gif" alt="link" title="link" /></a>
							<a href="javascript:imageTag('text');"><img src="js/yonas_editor/img/image.gif" alt="image" title="image" /></a>
							<a href="javascript:insertBreak('text');"><img src="js/yonas_editor/img/break.gif" alt="break" title="break" /></a>
						</div>

					<textarea id="text" rows="10" name="text" cols="60"><?php echo $articleData["text"]; ?></textarea>
					</div>

					<p id="formbuttons">
					<?php
					if($articleData["statusID"]!=2){//2=published
						echo "<input class='formbutton' type='submit' name='draft' value='Save Draft'>";
					}
					?>
					<input class="formbutton" type="submit" name="publish" value="Publish" >
					</p>

				</div><!-- writeform div -->


			<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->

			<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->
			<div id="rightcolumn">

				<div id="righttop">
				Categories
				</div><!-- righttop div -->

				<div class="right" id="categories">
					<?php


					foreach ($categoriesList as $key => $value){
						echo "<input type='checkbox' value='$key' name=\"categories[]\" ";
						//if(!is_null($article)){
							foreach($articleCategories as $articleCategory){
								if ($articleCategory==$key){echo "checked='yes'";}
							}
						//}
						echo"> $value<br />";
					}


					?>
				</div><!-- right div -->

				<div id="righttop">
				Author
				</div><!-- righttop div -->

				<div class="right">
					<select name="author">
						<?php

						foreach ($usersList as $key => $value){

						echo "<option value='$key' ";
						if($key==$articleData['authorID']) echo "selected";
							echo ">$value</option>";
						}

						?>
					</select>

				</div><!-- right div -->
				
				<div id="righttop">
				Language
				</div><!-- righttop div -->

				<div class="right">
					<select name="language" onchange ="getData('ajaxarticle.php?language='+this.value<?php 
					if($article!=NULL){echo "+'&article=$article'";} ?>, 'categories')">
						<?php

						foreach ($languagesList as $key => $value){

						echo "<option value='$key' ";
						if($key==$articleData['languageID']) echo "selected";
						echo ">$value</option>";
						}

						?>
					</select>

				</div><!-- right div -->

				
				<?php if(!is_null($year)){?>
				<div id="righttop">
					<div class="righttopleft">
						Date
					</div>

				</div><!-- righttop div -->
				<div class="right">
					
					<p class="formlabelsmall">Day / Month / Year</p>
					<input class="inputdate" type="text" name="day" size="2" maxlength="2" <?php
						echo "value='".$day."'";
					?>>
					<input class="inputdate" type="text" name="month" size="2" maxlength="2" <?php
						echo "value='".$month."'";
					?>>
					<input class="inputdate" type="text" name="year" size="4" maxlength="5" <?php
						echo "value='".$year."'";
					?>>					
					
					<p class="formlabelsmall">Hour / Minute / Second</p>
					<input class="inputdate" type="text" name="hour" size="2" maxlength="2" <?php
						echo "value='".$hour[0]."'";
					?>>
					<input class="inputdate" type="text" name="minute" size="2" maxlength="2" <?php
						echo "value='".$hour[1]."'";
					?>>
					<input class="inputdate" type="text" name="second" size="2" maxlength="2" <?php
						echo "value='".$hour[2]."'";
					?>>
				</div><!-- right div -->
				<?php } //end if $year ?>
				
				<div id="righttop">
					<div class="righttopleft">
						Status
					</div>
				</div><!-- righttop div -->
				<div class="right">
					<?=$status?>
				</div><!-- right div -->


			</div><!-- rightcolumn div -->
			<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->


			</form>
		</div><!-- writeform div -->

	</div><!-- mainfull div -->
</div><!-- containerfull div -->
<?php PrintFooter(); ?>

</body>
</html>

