<?php require_once  VIEWPATH.'/include/header.php'; ?>
<link rel="stylesheet" type="text/css" href="css/colorbox.css" />
<!-- UserCreate Modal -->
<div class="md-modal colored-header custom-width md-effect-9" id="form-primary">
    <div class="md-content">
      <div class="modal-header">
		<h3>新增用户</h3>
		<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <form method="post" id="addform" action="<?php echo site_url('user/create')?>" parsley-validate novalidate> 
      <div class="modal-body form">
		<div class="form-group">
		  <label>用户名</label> <input type="text" name="username" parsley-trigger="change" required placeholder="用户名" class="form-control">
		</div>
		<div class="form-group">
		  <label>密码</label> <input id="pass1" type="password" name="password" placeholder="密码" required class="form-control">
		</div>
		<div class="form-group">
		  <label>重输密码</label> <input parsley-equalto="#pass1" type="password" name="passconf" required placeholder="密码" class="form-control">
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
<!-- PasswordReset Modal -->
<div class="md-modal colored-header custom-width md-effect-9" id="form1-primary">
    <div class="md-content">
      <div class="modal-header">
		<h3>重置密码</h3>
		<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <form method="post" id="resetform" action="<?php echo site_url('user/reset_password')?>" parsley-validate novalidate> 
      <div class="modal-body form">
		<div class="form-group">
		  <label>新密码</label> <input id="pass2" type="password" name="password" placeholder="密码" required class="form-control">
		</div>
		<div class="form-group">
		  <label>重输密码</label> <input parsley-equalto="#pass2" name="passconf" type="password" required placeholder="重输密码" class="form-control">
		</div>
      </div>
      <div class="modal-footer">
      	<font color="red" class="tips" style="float: left;"></font>
      	<input type="hidden" name="id" />
		<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">取消</button>
		<button class="btn btn-primary btn-flat" type="submit" data-dismiss="modal">重置</button>
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
		<form method="get" action="<?php echo site_url('user/index')?>">
        <label><input type="text" class="form-control" placeholder="用户名" name="name" value="<?php echo $this->input->get('name');?>"></label>
        <label><button type="submit" class="btn btn-primary btn-rad">查询</button></label>
        <label><button type="button" class="btn md-trigger" data-modal="form-primary">新增用户</button></label>
        </form>
       
		<table class="hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>用户名</th>
					<th>类型</th>
					<th>状态</th>
					<th>创建时间</th>
					<th width="190">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($user as $u):?>
				<tr>
					<td><?php echo $u['id']?></td>
					<td><?php echo $u['name']?></td>
					<td><?php echo $type[$u['type']]?></td>
					<td><?php echo $state[$u['state']]?></td>
					<td><?php echo $u['create_time']?></td>
					<td>
						<a style="cursor: pointer;" class="md-trigger reset" data-modal="form1-primary" value="<?php echo $u['id']?>">重置密码</a>
						<a href="<?php echo site_url('user/clean_acl/' . $u['id']);?>" onClick="if (!confirm('确定清空？')){return false;}">离职清权限</a>
						<a class="bind" href="<?php echo site_url('user/set_role/' . $u['id'])?>">分配角色</a>
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
	</div>
</div>
<?php require_once  VIEWPATH.'/include/footer.php'; ?>
<script type="text/javascript" src="js/jquery.niftymodals/js/jquery.modalEffects.js"></script>
<script src="js/jquery.parsley/parsley.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.colorbox.js"></script>
<script type="text/javascript">
$(function(){
	$(".reset").click(function(){
		var id = $(this).attr("value");
		$("#form1-primary").find("input[name='id']").val(id);
	});
	$("#addform,#resetform").submit(function(){
		var self = $(this);
   		$.post(self.attr("action"), self.serialize(), success, "json");
   		return false;

   		function success(data){
   			if(data.ack){
   				self.find(".tips").html(data.msg + '，正在跳转...');
   				setTimeout(function(){ parent.location.reload(); },2000);
   			} else {
   				self.find(".tips").html(data.msg);
   			}
   		}
	});
	$(".bind").colorbox({width:"40%", height:"80%", iframe:true});
});
</script>