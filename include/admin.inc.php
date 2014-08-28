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


function PrintHeader($webtitle){

	echo "<div id='header'>";
	echo "<div id='header-left'>";
	echo htmlentities($webtitle);
	echo("</div><!-- header left div -->
	<div id='header-right'>
	<a href='../'><i>visit site</i></a> - <a href='./logout.php'><i>log out</i></a>
	</div><!-- header right div -->
	</div><!-- header div -->");
}

function PrintMenu1($tabNumber){

	echo"<div id='menu1'>
	<ul>
	";
	echo "	<li><a href='./article.php' ";
	if($tabNumber==1) echo "id='selected'";
	echo "><span>Create</span></a></li>";
	
	echo "	<li><a href='./manage.php' ";
	if($tabNumber==2) echo "id='selected'";
	echo "><span>Manage</span></a></li>";
	
	echo "	<li><a href='./globalsettings.php' ";
	if($tabNumber==3) echo "id='selected'";
	echo "><span>Settings</span></a></li>";
	echo "
	</ul>
	</div><!-- menu1 div -->";

}

function PrintFooter(){

	echo"<div id='footer'>\n";
	echo"powered by <a href='http://yonas.binarynonsense.com'>Yonas CMS</a>\n";
	echo "</div><!-- footer div -->\n";

}

?>
