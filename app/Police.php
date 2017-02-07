<?php

namespace App;

use \App\Model;
use Illuminate\Support\Facades\View;

class Police 
{    
    
  public static function getKeys() {
  	
  	$keys = [];
  	$keys['network'] = config( 'permission_keys.network' );
 
    return $keys;

  }

  public static function getAllKeys() {
    
    $keys = [];
    $keys['network'] = config( 'permission_keys.network' );

    $allkeys = [];
 
    foreach ( $keys['network'] as $c => $data ) {

      foreach ( $data as $key => $key_data ) {

        $allkeys[$key] = $key_data;

      }

    }

    return $allkeys;

  }  

  public static function check( $args ) {

    if ( !is_array( $args ) || ( is_array( $args ) && !empty( $args['keystring'] ) ) ) {

      $keystring = !is_array( $args ) ? explode( '.', $args ) : ( !empty( $args['keystring'] ) ? explode( '.', $args['keystring'] ) : [] );
      $args = is_array( $args ) ? $args : [];

      if ( count( $keystring ) > 2 ) {

        $args['section'] = $keystring[0];
        $args['category'] = $keystring[1];
        $args['key'] = $keystring[2];

      } elseif ( count( $keystring ) > 1 ) {

        $args['category'] = $keystring[0];
        $args['key'] = $keystring[1];

      } else {

        $args['key'] = $keystring[0];
      
      }

    }
      
    $section = arg( $args, 'section', 'network' );
    $category = arg( $args, 'category', 'people' );
    $key = arg( $args, 'key' );

    $network_id = arg( $args, 'network_id', 0 );

    $network = Networks::find( $network_id );

    $debug = [ 'section' => $section, 'category' => $category, 'key' => $key ];

    if ( !$network ) self::handleReturn( [ 'result' => [ 'allow' => false, 'message' => ___( 'Network was not found.' ), 'debug' => $debug ], 'args' => $args ] );

    $user_id = arg( $args, 'user_id', get_user_id() );
    $person = NetworkRelations::where( 'user_id', $user_id )->where( 'network_id', $network_id )->first();

    if ( !$person ) self::handleReturn( [ 'result' => [ 'allow' => false, 'message' => ___( 'Person was not found in network.' ), 'debug' => $debug ], 'args' => $args ] );

    $debug = [ 'section' => $section, 'category' => $category, 'key' => $key, 'user_id' => $user_id, 'person' => $person ];

    $keys = self::getKeys();

    $debug['keys'] = $keys;

    if ( empty( $keys[$section][$category][$key] ) ) return self::handleReturn( [ 'result' => [ 'allow' => false, 'message' => ___( 'Permission denied because of an invalid key request.' ), 'debug' => $debug ], 'args' => $args ] );

    $permissions = (array) try_json_decode( $person->permissions );

    if ( in_array( "network_owner", $permissions ) ) return self::handleReturn( [ 'result' => [ 'allow' => true, 'message' => ___( 'Permission granted based on exclusive access.' ), 'debug' => $debug ], 'args' => $args ] );
    
    if ( in_array( $key, $permissions ) ) return self::handleReturn( [ 'result' => [ 'allow' => true, 'message' => ___( 'Permission granted based on an included key.' ), 'debug' => $debug ], 'args' => $args ] );

    self::handleReturn( [ 'result' => [ 'allow' => false, 'message' => ___( 'Permission denied because there were no records found that would permit.' ), 'debug' => $debug ], 'args' => $args ] );

  }

  public static function handleReturn( $A ) {

    $return = arg( arg( $A, 'args' ), 'return' );
    $result = $A['result'];

    $accessmessage = ___( "Sorry, you do not have permission to do that. Please contact an administrator. [{$A['result']['debug']['key']}]" );

    $d = arg( $result, 'debug', [] );
    $ks = arg( $d, 'keys', [] );
      $k = arg( $d, 'key', '' );
      $s = arg( $d, 'section' );
      $c = arg( $d, 'category' );
    $p = arg( arg( arg( $ks, $s, [] ), $c, [] ), $k ) ;
    $m = arg( $p, 'message', '' );

    if ( arg( arg( $A, 'args' ), 'quickcheck' ) ) return $result['allow'];

    if ( $return ) {

      if ( !$result['allow'] ) {

        if ( $m ) $accessmessage = $m;

        $result['errors'] = $accessmessage;

      } else {

        return;

      }

      print_r( json_encode( $result ) );
      die;

    }

    $redirect = arg( arg( $A, 'args' ), 'redirect' );

    if ( $redirect ):

      header( "Location: $redirect"); 
      die;

    endif;

    if ( !$A['result']['allow'] ) {

      if ( env( 'PERMISSION_DEBUG' ) ) {

        print_r( $result ); 
        die;

      }

      print_r(View::make('layouts.app', compact( 'result' ) )->nest('child', 'blocked')->render());

      die;
    
    }

    $result['errors'] = $accessmessage;

    return arg( $A, 'result', [ 'allow' => false, 'message' => ___( 'Permission denied, possibly due to a system error.' ) ] );

  }

}
