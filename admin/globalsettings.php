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
include_once("../include/adminglobalsettings.inc.php");

?>

<html>

<title>Global Settings - Admin Area</title>
 
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" href="theme/admin.css" type="text/css" media="screen" />
</head>

<body>

<?php 
PrintHeader($webTitle); 
PrintMenu1(3);
?>




<div id="containerfull">

	<form method="post" action="saveglobalsettings.php">

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->
	<div id="mainfull">

		<div id="mainheader">
			<a href='usersettings.php' >User Settings</a>
			<a href='globalsettings.php' id='tabselected'>Global Settings</a>
		</div><!-- mainheader div -->

		<div id="writeform">

			<p class="settingslabel">Web Title <a href="#">[?]<span>Write in the next field the text you want as title/name of the website</span></a>  :
			<input class="settingstext" type="text" name="webtitle" size="45" <?php
				$webTitle = str_replace("\"","&quot;",$webTitle); 
				echo "value=\"$webTitle\"";
			?>>
			</p>

			<p class="settingslabel">Main Page Settings</p>

			<p class="settingslabel">Articles to show <a href="#">[?]<span>Write in this field the number of articles you want to show in the index page</span></a>   :
			<input class="settingsnumber" type="text" name="show" size="1" <?php
				echo "value='$show'";
			?>>
			</p>

			<p class="settingslabel">Only show articles and categories in <a href="#">[?]<span>languages for articles and categories <br /> in main page</span></a>   :
			<select name="showlanguages">
			<?php
				
				echo "<option value='0' ";
				if($key==0){
					echo "selected";
				}
				echo ">All Languages</option>";
				
				foreach ($languagesList as $key => $value){
					echo "<option value='$key' ";
					if($key==$showlanguages) echo "selected";
					echo ">$value</option>";
				}
			?>
			</select>
			</p>


			<p id="formbuttons">
				<input class="formbutton" type="submit" name="save" value="Save Global Settings">
			</p>
			 
			 
		</div><!-- writeform div -->

		</div><!-- main div -->

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->


	</form>
</div><!-- containerfull div -->

<?php PrintFooter(); ?>

</body>
</html>
