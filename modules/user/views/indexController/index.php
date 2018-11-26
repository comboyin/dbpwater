<?php 
/* @var $user User */
$user = $user;
$idacc = $user->getId();
$pictures = $user->getPictures();
?>
<div class="row">
                	<div class="col-sm-12">
                		<div class="form-group">
                			<a href="<?php echo __FOLDER . 'user/index/friendList'?>">
                				<input type="button" class="theme_button" name="apply_coupon" value="Friend list (<?php echo $user->getTotalFriendList()?>)">
                			</a>
	                    	
	                    	<a>
	                    		<input type="button" class="theme_button" name="update_cart" value="Favorite (<?php echo $user->getTotalFavorite()?>)">	
	                    	</a>
                    		
	               		</div>
                	</div>
</div>
<div class="row">
                    <div class="col-sm-12">
                        <div id="product-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active">
                                    <a href="#tab-introduction" role="tab" data-toggle="tab">Introduction</a>
                                </li>
                                <li >
                                    <a href="#tab-Picture" role="tab" data-toggle="tab">Picture</a>
                                </li>
                                <li >
                                    <a href="#tab-Location" role="tab" data-toggle="tab">Location</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab-introduction">
                                		sadasd
                                </div>

                                <div class="tab-pane fade" id="tab-Picture">
					                  <div id="product_listing">
					                    <div class="row">
					                    	<div class="col-sm-3 shop-product add-picture">
											    <div class="product-wrapper">
					                                <div class="product-image">
					                                    <a href="">
					                                        <img alt="" src="<?php echo __FOLDER  . 'public/img/friend_finder.png' ?>" >
				                                    </a>
				                                </div>
				                            </div>
				                        </div>
				                        
				                    	<span class="listPicture">
				                    		<?php  /* @var $picture Picture */ ?>
				                    		<?php foreach ($pictures as $picture):?>
				                    		<?php
				                    		
				                    		$is_like = $picture->is_like( $idacc );
				                    		$class_icon_thumbs = ( $is_like == false ) ? 'fa-thumbs-o-up' : 'fa-thumbs-o-down';
				                    		$data_original_title = ( $is_like == false ) ? 'Like' : 'Unlike';
				                    		
				                    		?>
				                    		
				                    		<div class="col-sm-3 shop-product">
											    <div class="product-wrapper">
					                                <div class="product-image">
					                                    <a href="">
					                                        <img alt="" src="<?php echo $picture->getViewUrl() ?> " >
					                                    </a>
					                                </div>
					                                <div class="product-details">
					                                    <div class="row">
					                                        <div class="col-xs-12">
					                                            <div class="product-tools">
					                                                <a id-picture="<?php echo $picture->getId()?>" href="#" title="Delete" data-toggle="tooltip">
					                                                    <i class="fa fa-remove"> | </i>
					                                                </a>
					                                                <a href="#" title="View" data-toggle="tooltip">
					                                                    <i class="fa fa-eye ">(<?php echo $picture->getView()?>) |</i>
					                                                </a>
					                                                <a href="#" title="<?php echo $data_original_title?>" data-toggle="tooltip">
					                                                    <i class="fa <?php echo $class_icon_thumbs ?>">(<?php echo $picture->getLikeNumber()?>)</i>
					                                                </a>
					                                            </div>
					                                        </div>
					                                    </div>
					                                </div>
					                            </div>
				                        	</div>
				                    		<?php endforeach;?>
					                    	</span>
					                    	
					                        </div>   
					                    </div>
									</div>
									
									<div class="tab-pane fade" id="tab-Location">
                                		sadasd
                                	</div>
                                	
                                </div>
                            </div>
      </div>
</div>


<!--=============================BEGIN DIALOG ADD LIST PICTURE================================================-->
		<div id="dialog-add-list-picture" title="Add Pictures">
			<div class="error_picture">
			
			</div>
			  <div>
				   <table style="margin: 0 auto;">
				   		<caption>PICTURE (JPEG, GIF, and PNG files up to 700kb)</caption>
				      <tbody>
				        <tr class="list-image">
							<td>Pictures</td>
				            <td>
								<input type="file" maxlength="10" accept="gif|jpg|png" name="pictures[]" class="form-control multi with-preview" multiple />
						 	</td>
						 </tr>
						 <tr>
				            <td colspan="2" style="text-align: center;">
				            	<input class="btn btn-success" name="submit_add_picture" value="Add">
				            	<span style="display:none" class="progress-loading-picture"><img src="<?php echo __FOLDER . 'public/img/AjaxLoader.gif'?>"></span>
				            </td>
				         </tr>
				      </tbody>
				   </table>
			</div>
		</div>
<!--=============================BEGIN DIALOG ADD LIST PICTURE================================================-->
  
<script type="text/javascript" src="<?php echo __FOLDER . 'public/js/UserModule/indexController/indexAction.js'?>"></script>
