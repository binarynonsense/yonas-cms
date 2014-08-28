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
include_once("../include/adminusersettings.inc.php");

?>

<html>

<title>User Settings - Admin Area</title>
 
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

	<form method="post" action="saveusersettings.php">

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->
	<div id="mainfull">

		<div id="mainheader">
			<a href='usersettings.php'  id='tabselected'>User Settings</a>
			<a href='globalsettings.php'>Global Settings</a>
		</div><!-- mainheader div -->

		<div id="writeform">
		
			<?php echo "<input type='hidden' name='userid' value='".$userData['ID']."'>"; ?>
		
			<p class="settingslabel">User Name:  <a href="#">[?]<span>Write in the next field your nickname, which is the name you'll use to log in</span></a></p>

			<input class="settingstext" type="text" name="username" size="45" <?php
				
				echo "value='".utf8_encode ($userData['username'])."'";
			?>>

			<p class="settingslabel">Full Name:  <a href="#">[?]<span>Write in the next field your full name</span></a></p>

			<input class="settingstext" type="text" name="fullname" size="45" <?php
				
				echo "value='".$userData['fullname']."'";
			?>>

			<p class="settingslabel">Email:  <a href="#">[?]<span>Write in this field your contact email (not shown in the public page)</span></a></p>
			<input class="settingstext" type="text" name="mail" size="45" <?php
				
				echo "value='".$userData['mail']."'";
			?>>
			
			<p class="settingslabel">New Password:  <a href="#">[?]<span>If you want to change your password type the new one here</span></a></p>
			
			<input class="settingstext"  type="password" name="newpassword" size="45">
			
			<p class="settingslabel">Status:&nbsp;&nbsp;
			<select name="authorstatus">
						<?php

						echo "<option value='". $userData['groupID']. "' selected >" . $groupsList[$userData['groupID']] . "</option>";

						?>
			</select></p>


			<p id="formbuttons">
				<input class="formbutton" type="submit" name="save" value="Save User Settings">
			</p>
			 
			 
		</div><!-- writeform div -->

		</div><!-- main div -->

	<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------->


	</form>
</div><!-- containerfull div -->

<?php PrintFooter(); ?>

</body>
</html>
