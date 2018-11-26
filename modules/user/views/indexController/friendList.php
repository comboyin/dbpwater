<?php 
$friendRelations = $friendRelations;
?>

<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
		<a href="<?php echo __FOLDER . 'user/index/friendList' ?>">
			<button class="theme_button">Favorite (<span class="total-friend"> <?php echo $totalFriend?> </span>)</button>
		</a>
		<a href="<?php echo __FOLDER . 'user/index/favorite'?>">
			<button class="theme_button">Favorite (<span class="total-favorite"> <?php echo $totalFavorite?> </span>)</button>
		</a> 
			
		</div>
	</div>
</div>

<div class="widget widget_popular_entries">
	<h3 class="widget-title">Friend list</h3>
	<div class="row list-friend">
	<?php /* @var $friendRelation Friend_relation */?>
		<?php foreach ( $friendRelations as $friendRelation ):?>
		
			<div class="col-xs-6">
				<div class="list-friend">
					<div class="media">
						<p class="pull-left">
							<a idfriend="<?php echo $friendRelation->getUserTo()->getId()?>" href="<?php echo __FOLDER . 'user/action/profile/' . $friendRelation->getUserTo()->getUsername()?>"><?php echo $friendRelation->getUserTo()->getFullname()?></a>
						</p>
						<a class="pull-left" href="#"> <img class="media-object"
							src="<?php echo $friendRelation->getUserTo()->getLinkAvatar()?>" alt="">
						</a>
						<p class="pull-left">
							<a class="Unfriend" href="#">Unfriend</a>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach;?>

	</div>
</div>

<html>
<script type="text/javascript" src="<?php echo __FOLDER . 'public/js/UserModule/indexController/friendListAction.js'?>"></script>

</html>