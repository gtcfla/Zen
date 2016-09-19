<?php require_once  VIEWPATH.'/include/blank.php'; ?>
<div class="block-flat">
	<form class="form-horizontal group-border-dashed" action="<?php echo site_url('role/set_nca_save')?>" method="post">
	<div class="header">							
		<h3>分配角色权限</h3>
		<div style="right: 55px; margin-top: -30px; position: fixed;">
			<select class="select2" name="role_id">
				<?php foreach ($role as $r):?>
				<option value="<?php echo $r['id']?>" <?php if ($this->uri->segment(3) == $r['id'])echo "selected='selected'"?>><?php echo $r['desc']?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div style="right: 5px; margin-top: -29px; position: fixed;"><button type="submit" class="btn btn-primary">确定</button></div>
	</div>
	<div class="content">
		<table class="table table-hover table-bordered">
		<thead>
			<tr>
				<th>Controller</th>
				<th>Action</th>
				<th>描述</th>
				<th>系统角色</th>
				<th class="text-center">显示</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($nca as $k => $n):?>
			<?php $i=0;?>
			<?php foreach ($n as $a):?>
			<tr>
				<td align="left"><?php if ($i == 0):?><label><input type="checkbox" class="checkbox-inline" id="<?php echo $k?>" /> <?php echo $k?></label><?php endif;?></td>
				<td align="left" title="<?php echo $a['desc']?>"><label><input type="checkbox" class="checkbox-inline" id="<?php echo $k.'-'.$a['id']?>" name="nca_id[]" value="<?php echo $a['id']?>" <?php if (in_array($a['id'], $nca_ids))echo "checked='checked'"?>/> <?php echo $a['action']?></label></td>
				<td align="left"><?php echo $a['desc']?></td>
				<td><?php echo $config[$a['role_access_control']]?></td>
				<td align="center"><?php echo $a['show'] ? '显示' : '';?></td>
			</tr>
			<?php $i++;?>
			<?php endforeach;?>
			<?php endforeach;?>
		</tbody>
		</table>
	</form>
	</div>
</div>
<?php require_once VIEWPATH.'/include/footer.php'; ?>
<script type="text/javascript">
$(function(){
	$("input[type='checkbox']").click(function(){
		var id = $(this).attr("id");
		if ($(this).is(":checked"))
		{
			$("input[id^='"+id+"-']").prop("checked", true);
		}
		else
		{
			$("input[id^='"+id+"-']").prop("checked", false);
		}
	});
	$("select[name='role_id']").change(function(){
		location.href = "<?php echo site_url('role/set_nca')?>/" + $(this).val();
	});
});
</script>