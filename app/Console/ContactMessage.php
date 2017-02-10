<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\ArgvInput;

class ContactMessage extends Command
{
  
  protected $signature = 'contactmessage {--details= : Enter encoded details.},
                                              ';
  protected $description = 'Insert new contact form results in database.';

  public function __construct() {
    
    parent::__construct();
  
  }

  public function handle() {
    
    $argv = new ArgvInput();
    $details = $argv->getParameterOption('--details');

    $details = @json_decode( @base64_decode( $details ) );

    $name = arg( $details, 'name' );
    $subject = arg( $details, 'subject' );
    $message = arg( $details, 'message' );
    $variables = arg( $details, 'variables' );

    DB::table( 'contact' )->insert( [ 'name' => $name, 'subject' => $subject, 'message' => $message, 'variables' => $variables, 'status' => 0 ] );
    
    $this->info( "Saved contact results." );
  
  }

}
