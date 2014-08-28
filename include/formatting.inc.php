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


//functions for text formatting

function string2Date($string){

	//ej: separates 20081201122029 into 2008 12 01 [12 20 29] => year month day [hour minute second]

	preg_match("/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$/", $string, $matches);
	//we have year in $matches[1] and month in $matches[2]
	$hour=array($matches[4],$matches[5], $matches[6]);
	return array($matches[1],$matches[2],$matches[3],$hour);
	//use example: list($year, $month, $day, $hour) = string2Date($filePath);
}

function addp($text, $br = 1) {

	//Based on code from http://ma.tt/scripts/autop/ 
	//for the wpautop function from wordpress' code: http://trac.wordpress.org/browser/trunk/wp-includes/formatting.php
	//which is licensed under GPL v2 license

	$text = $text . "\n"; // just to make things a little easier, pad the end
	$text = preg_replace('|<br />\s*<br />|', "\n\n", $text);
	// Space things out a little
	$allblocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
	$text = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $text);
	$text = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $text);
	$text = str_replace(array("\r\n", "\r"), "\n", $text); // cross-platform newlines
	if ( strpos($text, '<object') !== false ) {
		$text = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $text); // no text inside object/embed
		$text = preg_replace('|\s*</embed>\s*|', '</embed>', $text);
	}
	$text = preg_replace("/\n\n+/", "\n\n", $text); // take care of duplicates
	$text = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $text); // make paragraphs, including one at the end
	$text = preg_replace('|<p>\s*?</p>|', '', $text); // under certain strange conditions it could create a P of entirely whitespace
	$text = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $text);
	$text = preg_replace( '|<p>|', "$1<p>", $text );
	$text = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $text); // don't text all over a tag
	$text = preg_replace("|<p>(<li.+?)</p>|", "$1", $text); // problem with nested lists
	$text = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $text);
	$text = str_replace('</blockquote></p>', '</p></blockquote>', $text);
	$text = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $text);
	$text = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $text);
	if ($br) {
		$text = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<WPPreserveNewline />", $matches[0]);'), $text);
		$text = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $text); // optionally make line breaks
		$text = str_replace('<WPPreserveNewline />', "\n", $text);
	}
	$text = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $text);
	$text = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $text);
	if (strpos($text, '<pre') !== false)
		$text = preg_replace_callback('!(<pre.*?>)(.*?)</pre>!is', 'clean_pre', $text );
	$text = preg_replace( "|\n</p>$|", '</p>', $text );
	
	//new
	$text = str_replace("<p><a name='more'></a></p>", "\n<a name='more'></a>\n", $text);
	return $text;
	
	//REFERENCES:
	//http://ma.tt/scripts/autop/
	//http://trac.wordpress.org/browser/trunk/wp-includes/formatting.php
}


function string2Url($string){

	//based on code from http://bakery.cakephp.org/articles/view/adding-friendly-urls-to-the-cake-blog-tutorial

    // Define the maximum number of characters
        
    $maxURLLength = 100;
        
    $string = strtolower($string);
	
	
	$string = preg_replace('/&quot;/i', '-', $string);

	$string = strtr($string,
		"\xe1\xc1\xe0\xc0\xe2\xc2\xe4\xc4\xe3\xc3\xe5\xc5".
		"\xaa\xe7\xc7\xe9\xc9\xe8\xc8\xea\xca\xeb\xcb\xed".
		"\xcd\xec\xcc\xee\xce\xef\xcf\xf1\xd1\xf3\xd3\xf2".
		"\xd2\xf4\xd4\xf6\xd6\xf5\xd5\x8\xd8\xba\xf0\xfa".
		"\xda\xf9\xd9\xfb\xdb\xfc\xdc\xfd\xdd\xff\xe6\xc6\xdf",
		"aAaAaAaAaAaAacCeEeEeEeEiIiIiIiInNoOoOoOoOoOoOoouUuUuUuUyYyaAs");
	
	$string = preg_replace('/\s/i', '-', $string);
	$string = preg_replace('/[^a-z0-9\-]/i', '', $string);
	$string = preg_replace('/-[-]*/i', '-', $string);
        
    // Cut at a specified length
        
    if (strlen($string) > $maxURLLength)
    {
		$string = substr($string, 0, $maxURLLength);
    }
        
    // Remove beggining and ending signs
        
    $string = preg_replace('/-$/i', '', $string);
    $string = preg_replace('/^-/i', '', $string);
        
    return $string;
	
	//REFERENCES: 
	//http://es.php.net/strtr
} 
?>