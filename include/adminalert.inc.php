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

function alertBox($mode,$title='ERROR',$message=NULL,$returnLink=NULL){
	
	include("paths.inc.php");
	
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

	<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<title>Yonas CMS</title>
	<link rel="stylesheet" href="<?=$cmsPath?>/admin/theme/alert.css" type="text/css" media="screen" />

	</head>

	<body>

	
	<?php if($mode=='login'){//LOG IN BOX	
	?>
	<div id="content">
	<h1>Yonas CMS</h1>

	<h2> Login Required </h2>
	<p>You must log in to access the Admin area. If you have lost your password, contact the administrator of the web.</p>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<p> 
		<p>User ID:&nbsp;&nbsp;&nbsp;<input type="text" name="uid" size="20" /></p>
	    <p>Password: <input type="password" name="pwd" size="20" /></p>
	    <p><input type="submit" value="Log in" class="formbutton" /></p>
	</p>
	</form>
	</div><!-- end div content-->
	<?php }//end LOGIN BOX
	elseif($mode=='error'){//ERROR BOX
	?>
	<div id="content">
	<h1>Yonas CMS</h1>

	<h3><?=$title?></h3>
	<p><?=$message?> <a href="<?=$returnLink?>">here</a></p>
	</div><!-- end div content-->
	<?php }//end ERROR BOX
	elseif($mode=='ok'){//ERROR BOX
	?>
	<div id="content">
	<h1>Yonas CMS</h1>

	<h2><?=$title?></h2>
	<p><?=$message?> <a href="<?=$returnLink?>">here</a></p>
	</div><!-- end div content-->
	<?php }//end OK BOX
	?>
	</body>
	</html>
<?php 
exit;
}//end function alertBox
?>