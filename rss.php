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
//generate rss 0.91
//header("Content-Type: text/xml; charset=iso-8859-1");

include_once("include/paths.inc.php");
include_once("include/formatting.inc.php");
include_once("include/dbfunctions.inc.php");//extract data from db

$handle=dbOpen($dbPath);

//CONFIG DATA
$webTitle=dbWebTitle($handle);
//$show=dbNumber2Show($handle);
//$languagesByShortName=dbLanguagesByShortName($handle);
$showlanguages=0;
$articlesData=dbArticlesData($handle,0,5,true,false,$showlanguages);
// all done
// close database file
sqlite_close($handle);
			
$dom = new DOMDocument();

$tagRSS = $dom->createElement('rss');
$tagRSS->setAttribute('version', 0.91);
$dom->appendChild($tagRSS);

$tagChannel = $dom->createElement('channel');
$tagRSS->appendChild($tagChannel);

$tagTitle = $dom->createElement('title', $webTitle);
$tagLink  = $dom->createElement('link', $cmsPath);
$tagDesc  = $dom->createElement('description', 'the description');
$tagLang  = $dom->createElement('language', 'en');
$tagImage = $dom->createElement('image');
$tagChannel->appendChild($tagTitle);
$tagChannel->appendChild($tagLink);
$tagChannel->appendChild($tagDesc);
$tagChannel->appendChild($tagLang);
$tagChannel->appendChild($tagImage);

// Create three new elements that are needed to "describe" our image
$imageURL= 'http://localhost'.$cmsPath . 'themes/default/rss.jpg';
$tagImageURL = $dom->createElement('url',$imageURL);
$tagTitle = $dom->createElement('title', $webTitle);
$tagLink  = $dom->createElement('link', $cmsPath);
// Append to the image element
$tagImage->appendChild($tagImageURL);
$tagImage->appendChild($tagTitle);
$tagImage->appendChild($tagLink);
	
for($i=0;$i<count($articlesData);$i++) {

	$tagItem  = $dom->createElement('item');
	$tagTitle = $dom->createElement('title', $articlesData[$i]['title']);
	list($year, $month, $day, $hour) = string2Date($articlesData[$i]['date']);
	$url= "$year/$month/$day/" . string2Url($articlesData[$i]['title']) . "-" . $articlesData[$i]['ID'];
	$tagLink  = $dom->createElement('link', $url);
	//$tagDesc  = $dom->createElement('description', $articlesData[$i]['text']);

	// Append the nodes to the item, then append the item to the channel

	$tagItem->appendChild($tagTitle);
	$tagItem->appendChild($tagLink);
	//$tagItem->appendChild($tagDesc);

	$tagChannel->appendChild($tagItem);
	

}

header('Content-type: text/xml');

// dump
echo $dom->saveXML();

//references:
//http://www.talkphp.com/general/1234-creating-rss-documents-dom-api.html
?> 