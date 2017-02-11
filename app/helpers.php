<?php

function ___( $text, $locale = 'en' ) {

	return $text;
	
}

function get_user_id() {

	if ( Auth::check() ) return Auth::user()->id;

	else return 0;

}

function slugify($text)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;

}


function arg( $A, $key, $default = null ) {

  $A = (array) $A;

  if ( !empty( $A[$key] ) ) return $A[$key];

  return $default;

}

function try_json_decode( $string ) {

  $string = @json_decode( $string );
  
  if ( $string ) $object = $string;
  else $object = (object) [];

  return $object;

}

function orgpass( $args ) {

  return \App\Police::check( [ 'keystring' => "network.$args", 'quickcheck' => true ] );

}

function pass( $args, $network_id ) {

  $args = [ 'keystring' => "network.$args", 'quickcheck' => true, 'network_id' => $network_id ];

  return \App\Police::check( $args );

}

function block( $args, $project_id ) {

  return !pass( $args, $project_id );

}

function orgblock( $args, $project_id ) {

  return !orgpass( $args, $project_id );

}

function police( $args ) {

  return \App\Police::check( $args );

}