<?php require_once  VIEWPATH.'/include/blank.php'; ?>
<div class="block-flat">
	<div class="header">							
		<h3>分配用户角色</h3>
	</div>
	<div class="content">
	<form class="form-horizontal group-border-dashed" action="<?php echo site_url('user/set_role_save')?>" method="post">
		<div class="form-group">
			<label class="col-sm-3 control-label">用户名</label> <?php echo $user['name']?>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">状态</label> <?php echo $state[$user['state']];?>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">所属角色</label>
			<div class="col-sm-6">
              	<?php foreach ($role as $r):?>
				<label class="checkbox-inline"><input type="checkbox" <?php if (in_array($r['id'], $role_ids))echo "checked='checked'"?> name="role_ids[<?php echo $r['id']?>]" value="<?php echo $r['id']?>"> <?php echo $r['desc']?></label> 
			  	<?php endforeach;?>
			</div>
		</div>
		<div class="form-group">
	      	<label class="col-sm-3 control-label text-center">
	      		<input type="hidden" name="id" value="<?php echo $user['id']?>" />
	      	</label>
	      	<div class="col-sm-6" align="right">
			<button class="btn btn-primary btn-flat" type="submit" data-dismiss="modal">确定分配</button>
			</div>
		</div>
	</form>
	</div>
</div>
<?php require_once VIEWPATH.'/include/footer.php'; ?>