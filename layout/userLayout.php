<?php 
/* @var $user User */
$user = $_SESSION['acl']['account'];
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <title>NBOOK</title>
    <meta charset="utf-8">
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <![endif]-->
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="stylesheet" href="<?php echo __FOLDER . 'public/'?>js/vendor/jquery-ui-1.11.4/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo __FOLDER . 'public/'?>css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="<?php echo __FOLDER . 'public/'?>css/main.css" id="color-switcher-link">
    <link rel="stylesheet" href="<?php echo __FOLDER . 'public/'?>css/animations.css">
    <link rel="stylesheet" href="<?php echo __FOLDER . 'public/'?>css/fonts.css">
    <link rel="stylesheet" href="<?php echo __FOLDER . 'public/'?>css/customer.css">
    <link rel="stylesheet" href="<?php echo __FOLDER . 'public/'?>css/myjs.css">
    
    
    <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery-1.11.1.min.js"></script>
    <script src="<?php echo __FOLDER . 'public/'?>js/lib.js"></script>
    <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jQuery.MultiFile.min.js"></script>
    <script src="<?php echo __FOLDER . 'public/'?>js/vendor/alertJquery/dalert.jquery.min.js"></script>
    <script src="<?php echo __FOLDER . 'public/'?>js/vendor/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    
    <script src="<?php echo __FOLDER . 'public/'?>js/vendor/modernizr-2.6.2.min.js"></script>

    <!--[if lt IE 9]>
        <script src="js/vendor/html5shiv.min.js"></script>
        <script src="js/vendor/respond.min.js"></script>
    <![endif]-->

</head>
<body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

<!-- login modal -->

<div class="modal fade login-modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                 <h4 class="modal-title" id="loginModalLabel">N<span class="highlight">Book</span> Login</h4>
            </div>
            <form role="form" action="/">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Remember Me
                            </label>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="theme_button"><i class="rt-icon-ok"></i> Login</button>
                    <a href="#" class="theme_button" data-dismiss="modal"><i class="rt-icon-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="box_wrapper">

    <section id="topline" class="grey_section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <span>
                        <i class="fa fa-envelope-o"></i> <a href="mailto:comboyin1@gmail.com">comboyin1@gmail.com</a>
                    </span>
                    <span>
                        <i class="fa fa-phone"></i> +1 (900) 12345-123
                    </span>
                </div>
            </div>
        </div>
    </section>


    <section id="topinfo" class="action_section table_section light_section">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <a href="./" class="navbar-brand"> N<span>BOOK</span></a>
                </div>

                <div class="col-sm-5 text-right">
					    <div class="widget widget_search">
                            <form role="search" method="get" id="searchform" class="searchform form-inline" action="">
                                <div class="form-group">
                                    <label class="screen-reader-text" for="search">Search for:</label>
                                    <input type="text" value="" name="search" id="search" class="form-control" placeholder="Search...">
                                </div>
                                <button type="submit" id="searchsubmit" class="theme_button">Search</button>

                            </form>
                        </div>
                </div>
                <div class="col-sm-3 text-right">
					    <h3> Hi ! <?php echo $user->getUsername()?></h3>
                </div>




            </div>
        </div>
    </section>

    <header id="header">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <span id="toggle_mobile_menu"></span>
                    <nav id="mainmenu_wrapper">
                        <ul id="mainmenu" class="nav nav-justified sf-menu">
                            <li class="active">
                                <a href="index.html"><i class="rt-icon-home"></i> Home</a>
                            </li>
                            <li >
                                <a href="index.html"><i class="rt-icon-comment"></i> Friend request (2)</a>
                            </li>

                            <li >
                                <a href="index.html"><i class="rt-icon-list"></i> Follow list (20)</a>
                            </li>

                            <li>
                                <a href="<?php echo $router->url( array( 'module'=>'login','controller'=>'index','action' => 'logout' ) )?>"><i class="rt-icon-share"></i> Logout</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </header>

	<div class="container">
			<?php 
				/* @var $user User */
				$user = $user;
				$idacc = $user->getId();
				$pictures = $user->getPictures();
			?>
			<section class="light_section">
			    <div class="container">
			        <div class="row">
			            <div class="col-sm-12">
			                <div class="row">
			                    <div class="col-sm-4">
			                        <div class="text-center">
			                            <img id="product-image" src="<?php echo $user->getLinkAvatar();?>" >
			                            <button class="btn change-avatar">Change avatar</button>
			                        </div>
			                    </div>
			                    <div class="col-sm-8 single-product-description">
			                        <div class="product-prices">
			                            <div class="row">
			                                <div class="col-xs-10">
			                                    <span class="product-vars">
			                                    	<input style="display: none" type="text" value="" name="fullname" class="form-control" placeholder=""> 
			                                    	<h1>
			                                    		<strong>
			                                    			<span>
				                                    			<?php echo $user->getFullname()?>
			                                    			</span>
				                                    	</strong>
			                                    	</h1>
			                                    </span>
			                                </div>
			                                <div class="col-xs-2 text-right">
			                                    <span><a class="edit_info" href="#">Edit</a></span>
			                                    <span style="display: none" class="saveCancelInfo">
			                                    	<a class="Save_info" href="#">Save</a>
			                                    	<a class="Cancel_info" href="#">Cancel</a>
			                                    </span>
			                                </div>
			                            </div>
			                        </div>
			
									<div class="row">
										
											<div class="col-xs-6">
												<span class="product-vars">
				                            		<strong>
				                                		Email :
				                            		</strong>
			                            			
			                            			<span><?php echo $user->getEmail()?></span>
			                            			<input style="display: none" type="text" value="" name="email" class="form-control" placeholder="">
				                       		 	</span>
											</div>
											 <div class="col-xs-6 text-right">
			                                     	<span><a class="edit_info" href="#">Edit</a></span>
			                                    	<span style="display: none" class="saveCancelInfo">
				                                    	<a class="Save_info" href="#">Save</a>
				                                    	<a class="Cancel_info" href="#">Cancel</a>
			                                    	</span>
				                             </div>
			                             </span>
									</div>
			
			
									<div class="row">
			
										<div class="col-xs-6">
											<span class="product-vars">
					                            <strong>
					                                Sex :
					                            </strong>
			                            	
			                            		<span sex="<?php echo $user->getSex()?>"><?php echo $user->getStringSex()?></span>
			                            		
			                            		<div style="display: none;" class="input_sex">
			                            			<div  class="radio">
												  		<label><input value="1" type="radio" name="sex">Female</label>
													</div>
													
													<div class="radio">
												  		<label><input value="0" type="radio" name="sex">Male</label>
													</div>
													
			                            		</div>
				                            	
			                        		</span>
										</div>
			
										 <div class="col-xs-6 text-right">
			                                    <span><a class="edit_sex" href="#">Edit</a></span>
			                                    	<span style="display: none" class="saveCancelInfo">
				                                    	<a class="Save_sex" href="#">Save</a>
				                                    	<a class="Cancel_sex" href="#">Cancel</a>
			                                    </span>
			                             </div>
									</div>
									
									<div class="row">
			
										<div class="col-xs-6">
											<span class="product-vars">
			                            		<strong>
			                                		Birthday :
			                            		</strong>
			                            			<input style="display: none" type="text" value="" name="birthday" class="form-control" placeholder="yyyy-mm-dd">
			                            			<span><?php echo $user->getBirthday()?></span>
			                        		</span>
										</div>
			
										 <div class="col-xs-6 text-right">
			                                    <span><a class="edit_info" href="#">Edit</a></span>
			                                    	<span style="display: none" class="saveCancelInfo">
				                                    	<a class="Save_info" href="#">Save</a>
				                                    	<a class="Cancel_info" href="#">Cancel</a>
			                                    </span>
			                             </div>
									</div>
			
			
									<div class="row">
			
										<div class="col-xs-6">
											<span class="product-vars">
			                            		<strong>
			                            		    Address :
			                           		 </strong>
			                           		 	<input style="display: none" type="text" value="" name="address" class="form-control" placeholder="">
												<span><?php echo $user->getAddress()?></span>
			
			                        		</span>
										</div>
			
										 <div class="col-xs-6 text-right">
			                                    <span><a class="edit_info" href="#">Edit</a></span>
			                                    	<span style="display: none" class="saveCancelInfo">
				                                    	<a class="Save_info" href="#">Save</a>
				                                    	<a class="Cancel_info" href="#">Cancel</a>
			                                    </span>
			                             </div>
									</div>
			
									<div class="row">
			
										<div class="col-xs-6">
											<span class="product-vars">
			                            		<strong>
			                            		    Level :
			                           		 	</strong>
			                           		 	<span><?php echo $user->getGroup()->getStringLevel() ?></span>
			                        		</span>
										</div>
									</div>
			                      </div>
			
			                    </div>
			                </div>
			                </div>
						</div>
			</section>
			
			
			<!--=============================BEGIN DIALOG CHANGE AVATAR================================================-->
					<div id="dialog-change-avatar" title="Change avatar">
						<div class="error_change_avatar">
						
						</div>
						  <div class="border-style">
							   <table class="form_table">
							   		<caption>Avatar (JPEG, GIF, and PNG files up to 700kb)</caption>
							      <tbody>
							        <tr>
										<td>Avatar: </td>
							            <td>
											<input maxlength="1" type="file" accept="gif|jpg|png" name="avatar" class="form-control multi with-preview"/>
											<br/>
											<img class="avatar" style="max-height: 100px;display: none" src="">
									 	</td>
									 </tr>
			
							         <tr>
							            <td colspan="2" style="text-align: center;">
							            	<input class="btn btn-success" name="submit_change_avatar" value="Change">
							            	<span style="display:none" class="progress-loading"><img src="<?php echo __FOLDER . 'public/img/AjaxLoader.gif'?>"></span>
							            </td>
							         </tr>
							      </tbody>
							   </table>
						</div>
					</div>
			<!--=============================END DIALOG CHANGE AVATAR================================================-->

			<?php echo $content->render()?>
	</div>



    <footer id="footer" class="darkgrey_section">
        <div class="container">

        </div>
    </footer>


    <section id="copyright" class="dark_section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <p>&copy; Copyright 2014 - <span class="highlight">N</span>BOOK</p>
                </div>

            </div>
        </div>
    </section>


</div><!-- eof #box_wrapper -->

<div class="preloader">
    <div class="preloader_image"></div>
</div>


        <!-- libraries -->
        
        <script type="text/javascript" src="<?php echo __FOLDER . 'public/js/userLayout.js'?>"></script>
        
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.appear.js"></script>

        <!-- superfish menu  -->
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.hoverIntent.js"></script>
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/superfish.js"></script>

        <!-- page scrolling -->
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.easing.1.3.js"></script>
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.nicescroll.min.js'></script>
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.ui.totop.js"></script>
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.localscroll-min.js"></script>
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.scrollTo-min.js"></script>
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.parallax-1.1.3.js'></script>

        <!-- widgets -->
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.easypiechart.min.js"></script><!-- pie charts -->
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.countTo.js'></script><!-- digits counting -->
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.prettyPhoto.js"></script><!-- lightbox photos -->
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.plugin.min.js'></script><!-- plugin creator for comingsoon counter -->
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.countdown.js'></script><!-- coming soon counter -->
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.elevateZoom-3.0.8.min.js"></script><!-- zoom images -->
        
        

        <!-- sliders, filters, carousels -->
        <script src="<?php echo __FOLDER . 'public/'?>js/vendor/jquery.isotope.min.js"></script>
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/owl.carousel.min.js'></script>
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.fractionslider.min.js'></script>
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.flexslider-min.js'></script>
        <script src='<?php echo __FOLDER . 'public/'?>js/vendor/jquery.bxslider.min.js'></script>

        <!-- custom scripts -->
        <script src="<?php echo __FOLDER . 'public/'?>js/plugins.js"></script>
        <script src="<?php echo __FOLDER . 'public/'?>js/main.js"></script>

    </body>
</html>