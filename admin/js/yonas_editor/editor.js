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

/*
	functions for article's simple html source editor for Yonas CMS - yonas. binarynonsense.com
	
	last tested on: IE 6, Firefox 2.0.0.13  (29 march 2008)
*/

function showHideDiv(id) {
		var element = document.getElementById(id);
		if (element.style.display == 'none') {
			element.style.display = 'block';
		}
		else {
			element.style.display = 'none';
		}
}

function sourceEditorTab() {
		var source = document.getElementById('source');
		var visual = document.getElementById('visual');
		source.style.border = 'solid 2px #ccc';
		//source.style.color = '#666';		
		visual.style.border = 'solid 1px #ccc';
		//visual.style.color = '#C3C0C0';

}

function visualEditorTab() {
		var source = document.getElementById('source');
		var visual = document.getElementById('visual');
		source.style.border = 'solid 1px #ccc';
		//source.style.color = '#C3C0C0';
		visual.style.border = 'solid 2px #ccc';
		//visual.style.color = '#666';		

}

/////////////////// UI BUTTONS ////////////////////////

function insertTags(id,startTag,endTag) {
	//given an id (ej teaxarea id) inserts the startTag and endTag where the caret is
	//if there's some text selected, it goes between the tags
	 
	var area = document.getElementById(id);
	
    if (document.selection) {//Internet Explorer
	/*
	from: http://msdn2.microsoft.com/en-us/library/ms535869(VS.85).aspx
	-selection Object: Represents the active selection, which is a highlighted block of text or other elements in the document that a user or a script can carry out some action on.
		-methods:
			-createRange: Creates a TextRange object from the current text selection, or a controlRange collection from a control selection.
	note:		
	document.selection.createRange().text = "new text selection" -> to replace text in the current selection
	*/
        area.focus();
        var range = document.selection.createRange();
		var selectedText = range.text;
        range.text = startTag + selectedText + endTag;
		
    }else if (area.selectionStart!=null) {//Firefox...

        area.focus();
        var start = area.selectionStart;
        var end = area.selectionEnd;
		
		var selectedText = area.value.substring(start,end);
		selectedText = startTag + selectedText + endTag; 

		
        area.value = area.value.substring(0, start) + selectedText + area.value.substring(end, area.value.length);
        area.setSelectionRange(end + startTag.length + endTag.length, end + startTag.length + endTag.length);
		
    }else {
        area.value = startTag + area.value + endTag;
    }
}

function insertBreak(id) {
	 
	var area = document.getElementById(id);
	  
    if (document.selection) {//Internet Explorer
	
        area.focus();
        var range = document.selection.createRange();
		var selectedText = range.text;
        range.text = selectedText + '\n\n<!-- more -->\n\n';
    
    }else if (area.selectionStart!=null) {//Firefox...

        area.focus();
        var start = area.selectionStart;
        var end = area.selectionEnd;
		
		var selectedText = area.value.substring(start,end);
		selectedText = selectedText + '\n\n<!-- more -->\n\n';
        area.value = area.value.substring(0, start) + selectedText + area.value.substring(end, area.value.length);
        area.setSelectionRange(end + startTag.length + endTag.length, end + startTag.length + endTag.length);
		
    }else {
        area.value += '\n\n<!-- more -->\n\n';
    }
}

function imageTag(id) {
	
	var url = prompt('Insert image URL','http://'); 
	var area = document.getElementById(id);
	
    
    if (document.selection) {//Internet Explorer
        area.focus();

        sel = document.selection.createRange();
		var selectedText = sel.text;
        sel.text = selectedText + "<img src='" + url + "' />"; 
    
    } else if (area.selectionStart!=null) {//Firefox...

        area.focus();
        var start = area.selectionStart;
        var end = area.selectionEnd;
		
		var selectedText = area.value.substring(start,end);
		selectedText = selectedText + "<img src='" + url + "' />";
				
        area.value = area.value.substring(0, start) + selectedText + area.value.substring(end, area.value.length);
        area.setSelectionRange(end + startTag.length + endTag.length, end + startTag.length + endTag.length);
    } else {
        area.value += "<img src='" + url + "' />";  
    }
}


function linkTag(id) {
	
	var url = prompt('Insert URL','http://'); 
	var area = document.getElementById(id);
	   
    if (document.selection) {//Internet Explorer
	
        area.focus();
        sel = document.selection.createRange();
		var selectedText = sel.text;
        sel.text = "<a href='" + url + "'>" + selectedText + "</a>";
   
    } else if (area.selectionStart!=null) {//Firefox...

        area.focus();
        var start = area.selectionStart;
        var end = area.selectionEnd;
		
		var selectedText = area.value.substring(start,end);
		selectedText = "<a href='" + url + "'>" + selectedText + "</a>"; 
		
        area.value = area.value.substring(0, start) + selectedText + area.value.substring(end, area.value.length);
        area.setSelectionRange(end + startTag.length + endTag.length, end + startTag.length + endTag.length);
    } else {
        area.value += "<a href='" + url + "'>" + "</a>"; 
    }
}

//references:
//http://msdn2.microsoft.com/en-us/library/ms535869(VS.85).aspx
//http://developer.mozilla.org/en/docs/Talk:Gecko_DOM_Reference:Introduction
//http://www.quirksmode.org/dom/range_intro.html
//http://snippets.dzone.com/posts/show/4973  (area.selectionStart || area.selectionStart == '0')
//http://www.faqts.com/knowledge_base/view.phtml/aid/32250
//http://www.faqts.com/knowledge_base/view.phtml/aid/32427
//http://www.thescripts.com/forum/thread151663.html
//http://www.w3.org/TR/2000/REC-DOM-Level-2-Traversal-Range-20001113/ranges.html
