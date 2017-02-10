<?php

namespace App\Http\Controllers;

use App\Networks;
use App\NetworkRelations;
use App\Police;
use App\User;
use Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{

  public function __construct( Request $r )
  {

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

  public function checkLogin( Request $r ) {

    if ( Auth::check() ) return redirect( '/' );

    $email = $r->input( 'email' );

    if ( !$email ) return redirect( 'login' );

    $user = User::where( 'email', $email )->first();

    if ( $user ) {

      return redirect( '/login?email=' . $email );

    } else {

      return redirect( '/register?email=' . $email );
    
    }
    
    return redirect( '/' );
  
  }

  public function new() {

    return view( 'network.create' );

  }

  public function create( Request $r ) {

    $name = trim( $r->input( 'name' ) );
    $timezone = trim( $r->input( 'timezone' ) );
    $plan = trim( $r->input( 'plan' ) );
    
    $domain = slugify( $r->input( 'domain' ) );
  
    $err = null;

    if ( !$name ) {

      $err = [ 'errors' => ___( 'Please enter a name for your network.' ), 'target' => 'name' ];

    } elseif ( !$plan ) {

      $err = [ 'errors' => ___( 'Please enter a plan for this new network.' ), 'target' => 'plan' ];

    }elseif ( strlen( $name ) > 72 ) {

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
                      'domain' => $domain, 
                      'timezone' => $timezone,
                      'plan' => $plan 
                    ];

      $n = Networks::create( $newnetwork );
      $id = $n->id;

      $docroot = str_replace( "testmy", "test", base_path() );

      $idfill = str_pad( $id, 10, "0", STR_PAD_LEFT );
      $database = env( 'NETWORK_DATABASE_PREFIX' ) . "$idfill";

      $user = User::find( $user_id );

      if ( $user ) $username = $user->name;
      else $username = $name;

      $db_user = env( 'DB_USERNAME' );
      $db_password = env( 'DB_PASSWORD' );
      $db_host = env( 'DB_HOST' );

      $output = shell_exec( "mysql --host $db_host --user=$db_user --password=$db_password -e 'CREATE DATABASE $database' ");
      $output .= "\n\n" . shell_exec( "php $docroot/artisan migratenetwork --domain $domain");
      $output .= "\n\n" . shell_exec( "php $docroot/artisan networkconfig --database $database --sso_id $user_id --sso_name '$username' --sso_timezone '$timezone' --sso_network '$name'");

      Networks::find( $id )->update( [ 'status' => 1 ] );

      NetworkRelations::create( [ 'user_id' => $user_id, 'network_id' => $id, 'permissions' => '["network_owner"]', 'created_by' => $user_id ] );

      $newnetwork['url'] = 'http://' . $domain . '.' . env( 'APP_DOMAIN' ) . '/';

      $result = [ 'network' => $newnetwork, 'output' => $output ];

    }

    return response()->json( $result );

  }

  public function people( Request $r ) {

    $id = $r->route( 'network_id' );

    Police::check( [ 'keystring' => 'network.people.view_people', 'return' => $r->input( 'format' ) == 'json', 'network_id' => $id ] );

    $network = Networks::find( $id );
 
    $people = NetworkRelations::where( 'network_id', $id )->get();

    foreach ( $people as $p ) {

      $p->is_owner = $p->user_id == $network->user_id;

      $user = User::find( $p->user_id );

      if ( $user ) $p->name = $user->name;

    }

    if ( $r->input( 'format' ) == 'json' ) {

      return response()->json( [ 'people' => $people ] );

    }

    return view( 'network.people', compact( 'people', 'network' ) );

  }

  public function newPerson( Request $r ) {

    $id = $r->route( 'network_id' );
  
    Police::check( [ 'keystring' => 'network.people.create_person', 'network_id' => $id ] );

    $network = Networks::find( $id );

    return view( 'network.new-person', compact( 'network' ) );

  }

  public function createPerson( Request $r ) {

    $id = $r->route( 'network_id' );
  
    Police::check( [ 'keystring' => 'network.people.create_person', 'return' => 1, 'network_id' => $id ] );

    $network = Networks::find( $id );

    $email = trim( $r->input( 'email' ) );
    
    $err = null;

    if ( !$email ) {

      $err = [ 'errors' => ___( 'Please enter an email address to add the person to your network.' ), 'target' => 'email' ];

    }

    $user = User::where( 'email', $email )->first();

    if ( $user ) {

      $person_exists = NetworkRelations::where( 'user_id', $user->id )->count();

      if ( $person_exists ) $err = [ 'errors' => ___( 'This person already exists in your network.' ), 'target' => 'email' ];

    }

    if ( $err ) {

      $result = $err;

    } else {

      if ( $user ) {

        $newperson = [ 
                      'user_id' => $user->id, 
                      'network_id' => $id,
                      'created_by' => get_user_id()
                    ];

        $result_id = NetworkRelations::create( $newperson )->id;
        $username = $user->name;
        $user_id = $user->id;
        
        $docroot = str_replace( "testmy", "test", base_path() );
        $idfill = str_pad( $id, 10, "0", STR_PAD_LEFT );
        $database = env( 'NETWORK_DATABASE_PREFIX' ) . "$idfill";
        shell_exec( "php $docroot/artisan addperson --database $database --sso_id $user_id --sso_name '$username'");

        sleep(1);

        $url = 'http://my.' . env( 'APP_DOMAIN' ) . '/network/' . $id . '/person/' . $result_id . '/edit';

        $result = [ 'url' => $url ];

      } else {

        $result = [ 'errors' => ___( 'This person does not have an account in the ecosystem. You may invite them.' ), 'target' => 'email' ];

      }

    }

    return response()->json( $result );

  }

  public function getPerson( Request $r ) {

    $id = $r->route( 'network_id' );

    Police::check( [ 'keystring' => 'network.people.view_people', 'network_id' => $id, 'return' => 1 ] );

    $person_id = $r->route( 'id' );

    $person = NetworkRelations::find( $person_id );

    if ( !$person ) return [ 'errors' => ___( 'Person does not exist.' ) ];
    if ( $person->network_id != $id ) return [ 'errors' => ___( 'Person not found in network.' ) ];

    $user = User::find( $person->user_id );

    if ( !$user ) return [ 'errors' => ___( 'User not found.' ) ];

    $person->name = $user->name;

    $selected_permissions = try_json_decode( $person->permissions );

    if ( !$selected_permissions ) $selected_permissions = [];
    else $selected_permissions = (array) $selected_permissions;

    $permissionsCollection = Police::getAllKeys();

    foreach ( $permissionsCollection as $o => $o_info ) {

      $permission_info[$o] = $o_info;
      $permissions[] = $o;

    }

    return response()->json( [ 'permissions' => $permissions, 
                                'permission_info' => $permission_info, 
                                'selected_permissions' => $selected_permissions,
                                'person' => $person ] );

  }

  public function editPerson( Request $r ) {

    $id = $r->route( 'network_id' );
    $person_id = $r->route( 'id' );
  
    Police::check( [ 'keystring' => 'network.people.update_person', 'network_id' => $id ] );

    $network = Networks::find( $id );

    return view( 'network.edit-person', compact( 'network', 'person_id' ) );

  }

  public function updatePerson( Request $r ) {

    $id = $r->route( 'network_id' );
    $person_id = $r->route( 'id' );
  
    Police::check( [ 'keystring' => 'network.people.update_person', 'return' => 1, 'network_id' => $id ] );

    $network = Networks::find( $id );

    $result = [];
    $permissions = $r->input( 'permissions' );

    if ( !$permissions ) $permissions = '[]';
    else $permissions = json_encode( $permissions );

    if ( $permissions == '[]' ) {

      $people = NetworkRelations::where( 'network_id', $id )->count();

      if ( $people == 1 ) {

        $permissions = '["network_owner"]';
        $result = [ 'errors' => ___( 'You almost locked out yourself from the network. We restored exclusive access to avoid any disaster.' ) ];

      }

    }
    
    $changes = [ 
                  'permissions' => $permissions
                ];

    $person = NetworkRelations::find( $person_id );
    $person->update( $changes );

    $user = User::find( $person->user_id );

    if ( $user ) {

      $username = $user->name;
      $user_id = $user->id;
    
      $docroot = str_replace( "testmy", "test", base_path() );
      $idfill = str_pad( $id, 10, "0", STR_PAD_LEFT );
      $database = env( 'NETWORK_DATABASE_PREFIX' ) . "$idfill";
      shell_exec( "php $docroot/artisan editperson --database $database --sso_id $user_id --sso_name '$username' --sso_permissions '$permissions'");
        sleep(1);

    }

    $result = !$result ? [ 'success' => true ] : $result;

    return $result;

  }

  public function removePerson( Request $r ) {

    $id = $r->route( 'network_id' );
    $person_id = $r->route( 'id' );
  
    Police::check( [ 'keystring' => 'network.people.remove_person', 'return' => 1, 'network_id' => $id ] );

    $person = NetworkRelations::find( $person_id );

    $user = User::find( $person->user_id );

    if ( $user ) {

      $count = NetworkRelations::where( 'network_id', $id )->count();

      if ( $count == 1 ) {

        return [ 'errors' => ___( 'You almost locked out yourself from the network. We prevented you from removing yourself, being the only person in the network. Ouch!' ) ];

      }

      $username = $user->name;
      $user_id = $user->id;
    
      $docroot = str_replace( "testmy", "test", base_path() );
      $idfill = str_pad( $id, 10, "0", STR_PAD_LEFT );
      $database = env( 'NETWORK_DATABASE_PREFIX' ) . "$idfill";
      shell_exec( "php $docroot/artisan removeperson --database $database --sso_id $user_id");
        sleep(1);

    }

    $person->delete();

    $result = [ 'success' => true ];

  }

}
