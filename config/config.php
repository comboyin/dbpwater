<?php
$config = array (
		// database config
		'database' => array (
			/*  'db_servername' => "172.16.100.3",
				'db_username' => 'btwn2',
				'db_password' => 'btwn2',
				'db_dbname' => 'minh_nhut_lession_3' */
				'db_servername' => "localhost",
//				'db_username'   => 'root',
//				'db_password'   => 'lampart',
                'db_username'   => 'root',
                'db_password'   => '',
				'db_dbname'     => 'nbook'
		),

		// layout config
		'layout' => array (
				// = = = = = =   === error ========= = =============
				// error404Controller
				'error/error404' => array(
						'actions'=>array(

						),
						'default' => 'layout/error404Layout'
				),
				// denyController
				'error/deny' => array(
						'actions'=>array(

						),
						'default' => 'layout/denyLayout'
				),
				// controller login
				'login/index' => array(
						'actions'=>array(

						),
						'default' => 'layout/loginLayout'
				),
                'importdb/index' => array(
                    'actions'=>array(

                    ),
                    'default' => 'layout/importdbLayout'
                ),
				'user/index' => array(
						'actions' => array(

						),
						'default' => 'layout/userLayout'
				),
				'user/action' => array(
						'actions' => array(
				
						),
						'default' => 'layout/userLayout'
				)
		),

		// access controll list config
		'acl'=> array(
				// 0: guest , 1: Admin , 2: Operator , 3: User
				// allow config
				"allow"=>array(
						"user" => array(
							"index" => array(
								"all" => array(1,2,3)
							),
							"action" => array(
								"all" => array(1,2,3)	
							)
						),
						"login"=>array(
							"index" => array(
								"all" => "all"
							),
						),
                        "importdb"=>array(
                            "index" => array(
                                "all" => "all"
                            ),
                        ),
						"error" => array(
							"error404" => array(
								"all" => "all"
							),
							"deny" => array(
								"all" => "all"
							)
						)
				),
				// deny config
				"deny" => array(
						
				)
		),
		// pagination config
		'pagination' => array(
				'current_page' => 1,
				'total_record' => 1, // total record
				'total_page' => 1, // total page
				'limit' => 9, // limit record
				'start' => 0, // start record
				'link_full' => '', // link full: domain/page/{page}
				'link_first' => '', // link first page
				'range' => 9, // total button display
				'min' => 0, // Tham so min
				'max' => 0
		)
);