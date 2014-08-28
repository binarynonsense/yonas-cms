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

function validateArticle(){

	var categories = document.getElementsByName('categories[]');
	var title = document.getElementById('titletext');
	
	if(validateCheckboxes(categories,"You must check at least one category")){
		if(!isEmpty(title, "The article must have a title")){
			return true;
		}
	}
	
	return false;
	
}

function isEmpty(id, message){

	if(id.value.length == 0){
		alert(message);
		id.focus();
		return true;
	}
	return false;
	
}


function validateCheckboxes(id, message) {
	//at least one checkbox must be checked
	var arrayLength=id.length;
	for(var i=0;i<arrayLength;i++){
		if(id[i].checked){
		return true;
		}
	}
	alert(message); 
	id[0].focus();
	return false; 

}


//references:
// http://www.tizag.com/javascriptT/javascriptform.php
//http://www.webmasterworld.com/forum91/3989.htm