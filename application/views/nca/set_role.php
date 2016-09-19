<?php require_once  VIEWPATH.'/include/blank.php'; ?>
<div class="block-flat">
	<div class="header">							
		<h3>分配权限 - 绑定角色</h3>
	</div>
	<div class="content">
	<form class="form-horizontal group-border-dashed" action="<?php echo site_url('nca/set_role_save')?>" method="post">
		<div class="form-group">
			<table class=" class="hover"">
				<thead>
				<tr>
					<th>Controller</th>
					<th>Action</th>
					<th>描述</th>
					<th>系统角色</th>
					<th>显示</th>
					<th>排序 </th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><?php echo $nca['controller']?></td>
					<td><?php echo $nca['action']?></td>
					<td><?php echo $nca['desc']?></td>
					<td><?php echo $nca['role_access_control']?></td>
					<td><?php echo $nca['show'] ? '显示' : '隐藏'?></td>
					<td><?php echo $nca['sort']?></td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="form-group">
			<label class="control-label">所属角色</label>
			<div>
              	<?php foreach ($role as $r):?>
				<label class="checkbox-inline" style="margin-left: 0px!important;"> <input type="checkbox" <?php if (in_array($r['id'], $role_ids))echo "checked='checked'"?> name="role_ids[<?php echo $r['id']?>]" value="<?php echo $r['id']?>"> <?php echo $r['desc']?></label> 
			  	<?php endforeach;?>
			</div>
		</div>
		<div class="form-group">
	      	<label class="col-sm-3 control-label text-center">
	      		<input type="hidden" name="id" value="<?php echo $nca['id']?>" />
	      	</label>
	      	<div class="col-sm-9" align="right">
			<button class="btn btn-primary btn-flat" type="submit" data-dismiss="modal">确定</button>
			</div>
		</div>
	</form>
	</div>
</div>
<?php require_once  VIEWPATH.'/include/footer.php'; ?>