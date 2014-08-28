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


//functions to print useful xhtml elements
//TODO strip slashes

function htmlTitle($webTitle,$section) {

	return "<title>$section « $webTitle</title>\n";

}

function htmlMetaStyle($path) {

	return "<link rel='stylesheet' type='text/css' href='$path' />\n";

}

function htmlMetaAuthor($author) {

	return "<meta name='author' content='$author' />\n";

}

function htmlMetaDescription($description) {

	return "<meta name='description' content='$description' />\n";
	
}

function htmlMetaKeywords($keywords) {

	return "<meta name='keywords' content='$keywords' />\n";
	
}

function menu1($languageShort){
		include("include/paths.inc.php");
		echo "<a href='";
		echo $cmsPath;
		if($languageShort!=NULL){echo "$languageShort/";}
		echo "'>Index</a>";
		
		//XML - DOM
		$menu = new domdocument;
		$menu->preserveWhiteSpace = false;//don't allow whitespace as an object
		$menu->load("admin/menu1.xml");
		$items = $menu->getElementsByTagname("item");
		foreach ($items as $item) {
		/////////////////////////////////////////////////
			$type=$item->firstChild->nodeValue;
			$id=$item->lastChild->nodeValue;
			if($type=="page"){
				include_once("include/formatting.inc.php");
				include_once("include/dbfunctions.inc.php");
				$handle=dbOpen($dbPath);
				$pageData=dbPageData($handle,$id);
				sqlite_close($handle);
				$url=string2Url($pageData['title'])."-".$id."/";
				$name=$pageData['title'];
			}
			echo "<a href='";
			echo $cmsPath;
			echo $url;
			echo "'>";
			echo $name;
			echo "</a>";
		/////////////////////////////////////////////////
		}
}

/*<?=htmlMetaKeywords('Alvaro, García, Cuesta,Portfolio,Blog,comics,art,illustration, programming')?>*/

?>