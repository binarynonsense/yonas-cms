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

// references: http://www.ilovejackdaniels.com/php/caching-output-in-php/



//$cacheDir = $cmsPath .'cache/';//needs paths.inc.php
$cacheDir = './cache/';
$cacheDuration = 1*60*60; // life time of cached file in seconds


$fileExtension = 'cache'; // Extension to give cached files (usually cache, htm, txt)
$requestedPage = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$fileName=md5($requestedPage);


//at first i was creating folders to place the files but
//i can't do this in my server without giving the cache folder 777 permissions, which i'm not 
//willing to do

$dirName=substr($fileName, 0, 1);//place file in a folder which name is the first letter of the fileName to avoid performance issues when number of files is big
$fullPath = $cacheDir . $dirName . "/" . $fileName . '.' . $fileExtension; 


/*
$dirName=substr($fileName, 0, 1);//place file in a folder which name is the first letter of the fileName to avoid performance issues when number of files is big
if(!is_numeric($dirName)){
	$dirName = strtr($dirName,
		"abcdefghijklmnopqrstuvwxyz",
		"12345678912345678912345678");
}
$fullPath = $cacheDir . $dirName . "/" . $fileName . '.' . $fileExtension; 
*/

// when was it created?
$timeCreated = (@file_exists($fullPath)) ? @filemtime($fullPath) : 0;
@clearstatcache();

// cache file expired?
if (time() - $cacheDuration < $timeCreated) {

	echo "<!-- retrieved from cache -->";
	@readfile($fullPath);//show cached version
	exit();

}
// nothing in cache or it's too old
echo "<!-- generated just now (not retrieved from cache) -->";
ob_start();

?>
