<?php

namespace App\Http\Controllers;

use App\Networks;
use App\NetworkRelations;
use App\User;
use Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class NetworkController extends Controller
{

  public function __construct( Request $r )
  {

    if ( $r->input( 'request-type' ) == 'full-template' ) Config::set( 'pageconfig', 'full-template' );

  }

  public function index( Request $r ) {

    $user_id = get_user_id();

    $relations = NetworkRelations::where( 'user_id', $user_id )->get();

    if ( !$relations ) {

      $networks = [];

    } else {

      $networks = [];

      foreach ( $relations as $r ) {

        $network = Networks::find( $r->network_id );

        if ( $network ) {

          $network->is_owner = $network->user_id == $user_id;
          $network->url = "http://" . $network->domain . "." . env( 'APP_DOMAIN' ) . '/';
          $networks[] = $network;
        
        }

      }

    }

    return view( 'network.index', compact( 'networks' ) );

  }

  public function new() {

    return view( 'network.create' );

  }

  public function create( Request $r ) {

    $name = trim( $r->input( 'name' ) );
    
    $domain = slugify( $r->input( 'domain' ) );
  
    $err = null;

    if ( !$name ) {

      $err = [ 'errors' => ___( 'Please enter a name for your network.' ), 'target' => 'name' ];

    } elseif ( strlen( $name ) > 72 ) {

      $n = strlen( $name ) - 50;
      $chars = $n != 1 ? ___( 'characters' ) : ___( 'character' );

      $err = [ 'errors' => ___( 'Network name is too long. Delete' ) . " $n " . $chars . ___( ' to make it 50 long' ) . '.', 'target' => 'name' ];

    }

    $domain_exists = Networks::where( 'domain', $domain )->count();
    $invalid_domain = Networks::invalidDomain( $domain );

    if ( strlen( $domain ) < 3 ) {

      $err = [ 'errors' => ___( 'Your domain is too short. The minimum is 3 characters.' ), 'target' => 'domain' ];

    } elseif ( strlen( $domain ) > 64 ) {

      $err = [ 'errors' => ___( 'Your domain is too long. The maximum is 64 characters. Yours is now ' ) . strlen( $domain ) . ' ' . ___( "long" ) . '.', 'target' => 'domain' ];

    } elseif ( $domain_exists || $invalid_domain ) {

      $err = [ 'errors' => ___( 'You can keep the network name, but the domain is already in use. Please choose a different domain name.' ), 'target' => 'domain' ];

    }

    if ( $err ) {

      $result = $err;

    } else {

      $user_id = get_user_id();

      $newnetwork = [ 
                      'name' => $name, 
                      'user_id' => $user_id, 
                      'domain' => $domain 
                    ];

      $n = Networks::create( $newnetwork );
      $id = $n->id;

      $docroot = str_replace( "testmy", "test", base_path() );

      $idfill = str_pad( $id, 10, "0", STR_PAD_LEFT );
      $database = "dev_net_$idfill";

      shell_exec( "mysql --user=root --password=s3cr3t -e 'CREATE DATABASE $database' ");
      shell_exec( "php $docroot/artisan migratenetwork --domain $domain");
      shell_exec( "php $docroot/artisan createnetworkowner --database $database --sso_id $user_id --sso_name '$name'");

      Networks::find( $id )->update( [ 'status' => 1 ] );

      NetworkRelations::create( [ 'user_id' => $user_id, 'network_id' => $id ] );

      $newnetwork['url'] = 'http://' . $domain . '.' . env( 'APP_DOMAIN' ) . '/';

      $result = [ 'network' => $newnetwork ];

    }

    return response()->json( $result );

  }

}
