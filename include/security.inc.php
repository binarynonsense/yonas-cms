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

function isNumber($input){

   if (ereg("^[0-9]+$", $input)) {
   
      return true;
	  
   } else {
   
      return false;
	  
   }
   
} 

function isValidNick($input){

   if (ereg("^[0-9A-Za-z]+$", $input)) {
   
      return true;
	  
   } else {
   
      return false;
	  
   }
   
} 

function magicQuotes(){

	// Is magic quotes on? 
	if (get_magic_quotes_gpc()) { // Yes? Strip the added slashes 
	
	$_REQUEST = array_map('stripslashes', $_REQUEST); $_GET = array_map('stripslashes', $_GET); $_POST = array_map('stripslashes', $_POST); $_COOKIE = array_map('stripslashes', $_COOKIE); 
	
	}
	
}


define('SALT_LENGTH', 10);

function generateHash($password, $salt = null)
{
    if ($salt == null){
	
		//http://es2.php.net/uniqid
		//uniqid — Generate a unique ID
		//Description
		//string uniqid ([ string $prefix [, bool $more_entropy ]] )
		//Gets a prefixed unique identifier based on the current time in microseconds.

        $passwordHash['salt'] = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
		
    }
    else{
	
        $passwordHash['salt'] = substr($salt, 0, SALT_LENGTH);
		
    }
	
	$passwordHash['hash']=sha1($passwordHash['salt'] . $password);
	
    return $passwordHash; 
	
	//REFERENCES:
	//http://phpsec.org/articles/2005/password-hashing.html
	//http://www.topmost.se/personal/articles/casual-cryptography-for-web-developers.htm
}

?>