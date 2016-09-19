<?php require_once  VIEWPATH.'/include/header.php'; ?>
<link rel="stylesheet" type="text/css" href="css/colorbox.css" />
<!-- Nifty Modal -->
<div class="md-modal colored-header custom-width md-effect-9" id="form-primary">
    <div class="md-content">
      <div class="modal-header">
		<h3>新增角色</h3>
		<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <form id="addform" method="post" action="<?php echo site_url('role/create')?>" parsley-validate novalidate> 
      <div class="modal-body form">
		<div class="form-group">
		  <input type="text" name="desc" parsley-trigger="change" required placeholder="角色名" class="form-control">
		</div>
      </div>
      <div class="modal-footer">
      	<font color="red" class="tips" style="float: left;"></font>
		<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">取消</button>
		<button class="btn btn-primary btn-flat" type="submit" data-dismiss="modal">确定</button>
      </div>
      </form>
    </div>
</div>
<div class="md-overlay"></div>
<div class="block-flat">
	<div class="header">							
		<h3>查询条件</h3>
	</div>
	<div class="content">
        <label><button type="button" class="btn btn-primary btn-flat md-trigger" data-modal="form-primary">新增角色</button></label>
		<table class="hover">
			<thead>
				<tr>
					<th width="20">ID</th>
					<th>角色名</th>
					<th>创建时间</th>
					<th width="150">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($role as $r):?>
				<tr>
					<td><?php echo $r['id']?></td>
					<td><?php echo $r['desc']?></td>
					<td><?php echo $r['create_time']?></td>
					<td>
						<a href="<?php echo site_url('role/set_nca/' . $r['id']);?>" class="views">分配权限</a>
						<a href="<?php echo site_url('role/edit/' . $r['id']);?>" class="view">修改</a>
						<a href="<?php echo site_url('role/clean_acl/' . $r['id']);?>" onClick="if (!confirm('确定清空？')){return false;}">清空权限</a>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>
<?php require_once VIEWPATH.'/include/footer.php'; ?>
<script type="text/javascript" src="js/jquery.niftymodals/js/jquery.modalEffects.js"></script>
<script src="js/jquery.parsley/parsley.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.colorbox.js"></script>
<script type="text/javascript">
$(function(){
	$("#addform").submit(function(){
		var self = $(this);
   		$.post(self.attr("action"), self.serialize(), success, "json");
   		return false;

   		function success(data){
   			if(data.ack){
   				self.find(".tips").text(data.msg + '，正在跳转...');
   				setTimeout(function(){ parent.location.reload(); },2000);
   			} else {
   				self.find(".tips").text(data.msg);
   			}
   		}
	});
	$(".view").colorbox({width:"60%", height:"90%", iframe:true});
	$(".views").colorbox({width:"60%", height:"100%", iframe:true});
});
</script>