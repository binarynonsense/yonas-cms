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
include_once("../include/adminalert.inc.php");
session_start();
// destroy session's variables
$_SESSION = array();
// destroy the current session
session_destroy();
alertBox('ok','Successfully Logged Out',$message.'.<br />To go to the public area click ','../');
//echo "hecho: <a href='../'>go back to site</a>";
?>