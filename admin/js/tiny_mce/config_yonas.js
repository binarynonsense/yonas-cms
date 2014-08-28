/* *******************************************************************  

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

******************************************************************** */

function sourceEditor(id) {
	var elm = document.getElementById(id);

	if (tinyMCE.getInstanceById(id) != null){
		tinyMCE.execCommand('mceRemoveControl', false, id);
		showHideDiv('editorbuttons');
		sourceEditorTab();
		elm.focus();
	}
}

function visualEditor(id) {
	var elm = document.getElementById(id);

	if (tinyMCE.getInstanceById(id) == null){
		tinyMCE.execCommand('mceAddControl', false, id);
		showHideDiv('editorbuttons');
		visualEditorTab();
		elm.focus();
	}

}

tinyMCE.init({
	mode : "none",
	theme : "advanced",
	plugins : "pagebreak,table",
	pagebreak_separator : "<!-- more -->",
	content_css : 'js/tiny_mce/yonas.css',
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright, justifyfull,|,undo,redo,|,outdent,indent,|,link,unlink,image,pagebreak",
	theme_advanced_buttons2 : "bullist,numlist,sub,sup,|,tablecontrols",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	//theme_advanced_statusbar_location : "bottom",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
});