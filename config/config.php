<?php
$config = array (
        //environment config
        'environment' => array(
            'dev'    => '172.16.149.3',
            'pre'    => '',
            'debug1' => '',
            'debug2' => '',
            'debug3' => '',
            'test'   => '172.16.149.3'
        ),
        'server_db_name'  => array(
            'dev'    => 'pwater',
            'pre'    => 'pwater',
            'debug1' => 'pwater',
            'debug2' => 'pwater',
            'debug3' => 'pwater',
            'test'   => 'test'
        ),
        'server_db_pass'  => array(
            'dev'    => 'lampart',
            'pre'    => 'lampart',
            'debug1' => 'lampart',
            'debug2' => 'lampart',
            'debug3' => 'lampart',
            'test'   => 'lampart'
        ),
        'server_passphrase'  => array(
            'dev'    => 'lampart',
            'pre'    => 'lampart',
            'debug1' => 'lampart',
            'debug2' => 'lampart',
            'debug3' => 'lampart',
            'test'   => 'lampart'
        ),
        'server_auth_user'  => array(
            'dev'    => 'root',
            'pre'    => 'root',
            'debug1' => 'root',
            'debug2' => 'root',
            'debug3' => 'root',
            'test'   => 'root'
        ),
        'ssh_keyfiles'  => array(
            'pubkeyfile'  =>  __SITE_PATH . '/assets/ssh_keys/id_rsa.pub',
            'privkeyfile' => __SITE_PATH . '/assets/ssh_keys/id_rsa'
        ),
		// database config
		'database' => array (
			/*  'db_servername' => "172.16.100.3",
				'db_username' => 'btwn2',
				'db_password' => 'btwn2',
				'db_dbname' => 'minh_nhut_lession_3' */
				'db_servername' => "localhost",
				//'db_servername' => "172.16.49.2",
//				'db_username'   => 'root',
				'db_password'   => 'lampart',
                'db_username'   => 'root',
                //'db_password'   => '',
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
                'exportdb/index' => array(
                    'actions'=>array(

                    ),
                    'default' => 'layout/exportdbLayout'
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
                        "exportdb"=>array(
                            "index" => array(
                                "all" => array(1,2,3)
                            ),
                            "action" => array(
                                "all" => array(1,2,3)
                            )
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