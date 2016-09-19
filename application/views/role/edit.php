<?php require_once  VIEWPATH.'/include/blank.php'; ?>
<link rel="stylesheet" type="text/css" href="js/jquery.select2/select2.css" />
<div class="block-flat">
	<div class="header">							
		<h3>修改角色</h3>
	</div>
	<div class="content">
	<form class="form-horizontal group-border-dashed" action="<?php echo site_url('role/edit_save')?>" method="post">
		<div class="form-group">
			<label class="col-sm-3 control-label">角色名</label>
			<div class="col-sm-6">
				<input type="text" name="desc" value="<?php echo $role['desc']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">分配用户</label>
			<div class="col-sm-6">
				<select name="user_ids[]" class="select2" multiple>
              	<?php foreach ($user as $u):?>
				<option value="<?php echo $u['id']?>" <?php if (in_array($u['id'], $user_ids)) echo "selected='selected'"?>><?php echo $u['name']?></option>
			  	<?php endforeach;?>
			  </select>
			</div>
		</div>
		<div class="form-group">
	      	<label class="col-sm-3 control-label text-center">
	      		<input type="hidden" name="id" value="<?php echo $role['id']?>" />
	      	</label>
	      	<div class="col-sm-6" align="right">
			<button class="btn btn-primary btn-flat" type="submit" data-dismiss="modal">确定</button>
			</div>
		</div>
	</form>
	</div>
</div>
<script src="js/jquery.parsley/parsley.js" type="text/javascript"></script>
<?php require_once  VIEWPATH.'/include/footer.php'; ?>