<?php require_once VIEWPATH.'/include/header.php'; ?>
<link rel="stylesheet" type="text/css" href="css/colorbox.css" />
<div class="block-flat">
<div class="header">							
	<h3>查询条件</h3>
</div>
<form method="get" action="<?php echo site_url('nca/index')?>">
	<div class="form-group">
		<label><input type="text" class="form-control" placeholder="Controller" name="controller" value="<?php echo $this->input->get('controller');?>"></label>
		<label><input type="text" class="form-control" placeholder="Action" name="action" value="<?php echo $this->input->get('action');?>"></label>
        <label><button type="submit" class="btn btn-primary btn-rad">查询</button></label>
        <label><input type="button" class="btn" id="btn" value="保存修改" /></label>
    </div>
</form>
<form action="<?php echo site_url('nca/save')?>" method="post" id="form">
<table class="hover">
<thead>
<tr>
	<th width="130">Controller</th>
	<th>Action</th>
	<th>描述</th>
	<th width="160">系统角色</th>
	<th width="100">显示</th>
	<th width="60">排序 </th>
	<th>参数</th>
	<th width="44">操作</th>
</tr>
</thead>
<tbody>
<?php foreach ($nca as $n):?>
<tr>
	<td><?php echo $n['controller']?></td>
	<td><?php echo $n['action']?></td>
	<td><input type="text" class="form-control" name="desc[<?php echo $n['id']?>]" value="<?php echo $n['desc']?>" /></td>
	<td>
		<select name="role_access_control[<?php echo $n['id']?>]" class="select2">
			<option value="0" <?php if ($n['role_access_control'] == 0):?>selected="selected"<?php endif;?>>ACL_NULL</option>
			<option value="1" <?php if ($n['role_access_control'] == 1):?>selected="selected"<?php endif;?>>ACL_EVERYONE</option>
			<option value="2" <?php if ($n['role_access_control'] == 2):?>selected="selected"<?php endif;?>>ACL_HAS_ROLE</option>
			<option value="3" <?php if ($n['role_access_control'] == 3):?>selected="selected"<?php endif;?>>ACL_NO_ROLE</option>
		</select>
	</td>
	<td>
		<div class="switch switch-small">
			<input name="show[<?php echo $n['id']?>]" type="checkbox" <?php if($n['show']):?>checked<?php endif;?> />
		</div>
	</td>
	<td><input type="text" class="form-control text-center" size="3" name="sort[<?php echo $n['id']?>]" value="<?php echo $n['sort']?>" /></td>
	<td><input type="text" class="form-control text-center" size="3" name="param[<?php echo $n['id']?>]" value="<?php echo $n['param']?>" /></td>
	<td>
		<?php if ( ! $n['action']):?>
		<a href="<?php echo site_url('nca/index/'.$n['id'])?>">详细</a>
		<?php else:?>
		<a href="<?php echo site_url('nca/set_role/'.$n['id'])?>" class="assign">分配</a>
		<?php endif;?>
	</td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<ul class="pagination pagination-sm">
<?php echo $page;?>
<li class="disabled"><a>每页 <?php echo $per_page;?> 条</a></li>
<li class="disabled"><a>共 <?php echo $total_rows;?> 条</a></li>
</ul>
</form>
</div>
</div>
<?php require_once  VIEWPATH.'/include/footer.php'; ?>
<script type="text/javascript" src="js/jquery.colorbox.js"></script>
<script type="text/javascript">
$(function(){
	$(".assign").colorbox({width:"60%", height:"90%", iframe:true});
	$("#btn").click(function(){
		$("#form").submit();
	});
});
</script>