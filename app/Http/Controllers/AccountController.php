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

class AccountController extends Controller
{

  public function __construct( Request $r )
  {

  }

  public function index() {

    return view( 'auth.update' );

  }

  public function getAccount() {

    $user_id = Auth::user()->id;
    $account = User::find( $user_id );
    $details = [ 'email' => $account->email, 'name' => $account->name ];

    return response()->json( [ 'account' => $details ] );

  }

  public function logout() {

    setcookie( config( 'session.global_cookie' ), 'X', time() - ( 60 * 60 * 24 ), "/", "." . env( 'APP_DOMAIN' ) );
    setcookie( config( 'session.global_cookie' ), '', time() - ( 60 * 60 * 24 ), "/", "demo." . env( 'APP_DOMAIN' ) );

    setcookie( config( 'session.la_cookie' ), 'X', time() - ( 60 * 60 * 24 ), "/", "." . env( 'APP_DOMAIN' ) );
    setcookie( config( 'session.la_cookie' ), '', time() - ( 60 * 60 * 24 ), "/", "demo." . env( 'APP_DOMAIN' ) );

    Auth::logout();

    header( "Location: http://www." . env('APP_DOMAIN') );

    die;

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

  public function update( Request $r ) {

    $name = $r->input( 'name' );
    $email = $r->input( 'email' );
    $current_password = $r->input( 'current_password' );
    $password = $r->input( 'password' );
    $password2 = $r->input( 'password2' );
  
    $err = null;

    $user = Auth::user();

    if ( !$user ) return response()->json( [ 'errors' => ___( 'A VERY serious authentication error has occured. Please log in again.' ) ] );

    $user_id = $user->id;

    if ( !$name ) {

      $err = ___( 'Please enter a name.' );

    } elseif( !$email ) {

      $err = ___( 'Please enter an email address.' );

    } else {

      $usercount = User::where( 'email', $email )->where( 'id', '<>', $user_id )->count();

      if ( $usercount > 0 ) {

        $err = ___( 'The email address you entered is already taken.' );

      } else {

        // $userauth = User::where( 'password', bcrypt( $current_password ) )->where( 'id', Auth::user()->id )->count();
        $userauth = Auth::attempt( [ 'email' => $user->email, 'password' => $current_password ] );

        if ( !$userauth ) {

          $err = ___( 'Current password entered is incorrect.' );

        } else {

          if ( $password || $password2 ) {

            if ( $password2 != $password ) {

              $err = ___( 'New paasswords are not the same.' );

            } elseif ( strlen( $password ) < 8 ) {

              $err = ___( 'New paassword is too short. It must be at least 8 characters long.' );

            } else {

              // ToDo: implement a strong password rule.

            }

          }

        }

      }

    }

    if ( $err ) {

      return response()->json( [ 'errors' => $err ] );
    
    }
    
    $changes = [ 
                  'name' => $name,
                  'email' => $email,
                ];

    if ( $password ) $changes['password'] = bcrypt( $password );

    User::find( $user_id )->update( $changes );

    $docroot = str_replace( "testmy", "test", base_path() );

    $nets = NetworkRelations::where( 'user_id', $user_id )->get();

    foreach ( $nets as $net ) {

      $idfill = str_pad( $net->network_id, 10, "0", STR_PAD_LEFT );
      $database = env( 'NETWORK_DATABASE_PREFIX' ) . "$idfill";
      shell_exec( "php $docroot/artisan editperson --database $database --sso_id $user_id --sso_name '$name'");
        sleep(1);

    }

    $result = [ 'success' => true ];

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
