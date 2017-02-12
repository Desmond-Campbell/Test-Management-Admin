<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	// WARNING!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	// WARNING!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	// Don't truncate in production environment!!!!!!!!!!!!!!!
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	//
    	// WARNING !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    	//
    	//
    	//
    	//
    	//
    	// DO NOT TRUNCATE IN PRODUCTION ENVIRONMENT!!!!!!!!!!!!!!!!!!!!
    	//
    	//
    	//
    	//
    	//
    	//
    	//

    	////// LAST WARNING!!! LEAVE THE FOLLOWING ALONE IF IN PRODUCTION !!!!!!!!!!!!!!!

    	////////////////////////////////////////////////////////////////
    	////////////////////////////////////////////////////////////////////
    	////////////////////////////////////////////////////////////////
    	// DB::table('networks')->truncate();											//////////
    	// DB::table('network_relations')->truncate();						//////////
    	// DB::table('users')->truncate();												//////////
    	////////////////////////////////////////////////////////////////
    	////////////////////////////////////////////////////////////////////
    	////////////////////////////////////////////////////////////////
    	////////////////////////////////////////////////////////////////////
      
      $network = [
									"id" => 1,
									"name" => "Demo Network",
									"domain" => "demo",
									"user_id" => 1,
									"timezone" => "America/New_York",
									"status" => 1,
									"plan" => "Basic",
								];

			\App\Networks::create( $network );

			$users = '{
									"data":
									[
										{
											"id": 1,
											"name": "Vladimir Owner",
											"email": "demouser1@saastest.co",
											"password": "$2a$04$mOx7lrk5pYYFHTN5NYEKjemiRu/DfHPSPTptst4gEao.s74.e30uC",
											"remember_token": "",
											"created_at": "2017-02-07 01:20:44",
											"updated_at": "2017-02-07 01:20:44"
										},
										{
											"id": 2,
											"name": "Justin Admin",
											"email": "demouser2@saastest.co",
											"password": "$2a$04$mOx7lrk5pYYFHTN5NYEKjemiRu/DfHPSPTptst4gEao.s74.e30uC",
											"remember_token": "",
											"created_at": "2017-02-07 01:20:44",
											"updated_at": "2017-02-07 01:20:44"
										},
										{
											"id": 3,
											"name": "Dimitri Project",
											"email": "demouser3@saastest.co",
											"password": "$2a$04$mOx7lrk5pYYFHTN5NYEKjemiRu/DfHPSPTptst4gEao.s74.e30uC",
											"remember_token": "",
											"created_at": "2017-02-07 01:20:44",
											"updated_at": "2017-02-07 01:20:44"
										},
										{
											"id": 4,
											"name": "Nadine Supervisor",
											"email": "demouser4@saastest.co",
											"password": "$2a$04$mOx7lrk5pYYFHTN5NYEKjemiRu/DfHPSPTptst4gEao.s74.e30uC",
											"remember_token": "",
											"created_at": "2017-02-07 01:20:44",
											"updated_at": "2017-02-07 01:20:44"
										},
										{
											"id": 5,
											"name": "Gladys Tester",
											"email": "demouser5@saastest.co",
											"password": "$2a$04$mOx7lrk5pYYFHTN5NYEKjemiRu/DfHPSPTptst4gEao.s74.e30uC",
											"remember_token": "",
											"created_at": "2017-02-07 01:20:44",
											"updated_at": "2017-02-07 01:20:44"
										}
									]
								}';

			foreach ( ( (array) json_decode( $users ) )['data'] as $user ) {

				\App\User::create( (array) $user );

			}

			$network_relations = '{
														"data":
														[
															{
																"id": 1,
																"network_id": 1,
																"user_id": 1,
																"permissions": "[\"network_owner\"]",
																"created_by": 0
															},
															{
																"id": 2,
																"network_id": 1,
																"user_id": 2,
																"permissions": "[\"view_people\",\"view_projects\",\"view_all_projects\",\"create_project\"]",
																"created_by": 1
															},
															{
																"id": 3,
																"network_id": 1,
																"user_id": 3,
																"permissions": "[\"view_projects\",\"create_project\",\"view_all_projects\"]",
																"created_by": 1
															},
															{
																"id": 4,
																"network_id": 1,
																"user_id": 4,
																"permissions": "[\"view_people\",\"view_projects\",\"create_project\"]",
																"created_by": 1
															},
															{
																"id": 5,
																"network_id": 1,
																"user_id": 5,
																"permissions": "[\"view_projects\"]",
																"created_by": 1
															}
														]
													}';

			foreach ( ( (array) json_decode( $network_relations ) )['data'] as $relation ) {

				\App\NetworkRelations::create( (array) $relation );

			}

    }

}
