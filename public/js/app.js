var app = angular.module('testmy',  [ 'ngMaterial', 'angular-timezone-selector' ]);

function _alert( text, no_translate = false ) {

  alert( text );

}

function slugify(text)
{
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}


function to( u ) {

  location.assign( u );

}

function _tt( text ) {

	return text;
	
}