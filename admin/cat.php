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
// set path of database file
$db = "yonas.db";

// open database file
$handle = sqlite_open($db) or die("Could not open database");
$query = "SELECT * FROM article_category";
// execute query
$result = sqlite_query($handle, $query) or die("Error in query: ".sqlite_error_string(sqlite_last_error($handle)));
// if rows exist
if (sqlite_num_rows($result) > 0) {
	while($row = sqlite_fetch_array($result)){
	echo "$row[0] - $row[1]<br>";
	}
}

	// all done
	// close database file
	sqlite_close($handle);
?>