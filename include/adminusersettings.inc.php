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


//init code for user settings page

include_once("session.inc.php");

//check if magic quotes on, then stripslashes
magicQuotes();

//CONFIG DATA
$webTitle=dbWebTitle($handle);
//$numberArticles=dbNumberArticles($handle);
$groupsList=dbGroupsList($handle);
$userData=dbUserData($handle,1);

$userLoginDatabyName=dbUserLoginDatabyName($handle,'admin');

// all done
// close database file
dbClose($handle);

?>