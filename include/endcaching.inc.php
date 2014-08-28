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

// create cached file

//if folder doesn't exist: create it
//i can't use this in my server without giving the cache folder 777 permissions, which i'm not 
//willing to do
if(!file_exists($cacheDir . $dirName) ){
	mkdir($cacheDir . $dirName);
}
		
$fp = @fopen($fullPath, 'w');
@fwrite($fp, ob_get_contents());
@fclose($fp);

ob_end_flush();

?>
