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


//

include_once("security.inc.php");
include_once("adminalert.inc.php");
include_once("dbfunctions.inc.php");//extract data from db

// set path of database file
$dbPath = "yonas.db";
$handle=dbOpen($dbPath);

session_start();

$uid = isset($_POST['uid']) ? sqlite_escape_string($_POST['uid']) : $_SESSION['uid'];

if(!isset($uid)) {

  alertBox('login');
  
}

$userLoginDatabyName=dbUserLoginDatabyName($handle,$uid);

if(isset($_POST['pwd'])){

	$generated=generateHash(sqlite_escape_string($_POST['pwd']),$userLoginDatabyName['salt']);
	$hash=$generated['hash'];

}else{

	$hash=$_SESSION['hash'];
	
}

$_SESSION['uid'] = $uid;
$_SESSION['hash'] = $hash;

if ($userLoginDatabyName['hash']!=$hash) {

  unset($_SESSION['uid']);
  unset($_SESSION['hash']);
  
  alertBox('error','Access Denied','Your user ID or password is incorrect, or you are not a
     registered user on this site. To try logging in again, click',$_SERVER['REQUEST_URI']);
 
}

//REFERENCES:
//http://www.sitepoint.com/article/users-php-sessions-mysql
?>
