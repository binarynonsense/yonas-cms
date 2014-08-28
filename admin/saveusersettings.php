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
//This file receives the neccesary info from usersettings.php to save the user data
//TODO: check the input, allow only a-z,_ ,0-9 en nickname and min mum characters in password
//do it in javascript in usersettings.php

include_once("../include/session.inc.php");

//check if magic quotes on, then stripslashes
magicQuotes();

$userID=sqlite_escape_string(utf8_encode($_POST['userid']));
if(!isNumber($userID) && $userID>0){
	alertBox('error','ERROR',' User ID <b>must be a number</b>.<br />To go back click ',$_SERVER['HTTP_REFERER']);
}

$userName=sqlite_escape_string(utf8_encode($_POST['username']));
if(!isValidNick($userName)){
	alertBox('error','ERROR',' User Nickname can only contain <b>letters</b>, "a-z, A-Z", and <b>underscores</b>, "_".<br />To go back click ',$_SERVER['HTTP_REFERER']);
}
$fullName=sqlite_escape_string(utf8_encode ($_POST['fullname']));
$mail=sqlite_escape_string(utf8_encode ($_POST['mail']));

$message.="User Nickname: ".utf8_decode($userName)."<br />";
$message.="User Name: ".utf8_decode($fullName)."<br />";

$userLoginData=dbUserLoginData($handle,$userID);

$query="UPDATE users SET username='$userName',name='$fullName',mail='$mail'";

if($_POST['newpassword']!=NULL){

	$newPassword=sqlite_escape_string(utf8_encode ($_POST['newpassword']));
	$passwordHash=generateHash($newPassword);
	$message.="Password updated.<br />";
	$query.=",hash='".$passwordHash['hash']."',salt='".$passwordHash['salt']."'";
}

$query.=" WHERE ID='$userID'";

sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));

$userLoginData=dbUserLoginData($handle,$userID);

alertBox('ok','User data successfully updated',$message.'.<br />To go back click ',$_SERVER['HTTP_REFERER']);
	
// all done
// close database file
sqlite_close($handle);




?>
