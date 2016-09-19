<?php require_once  VIEWPATH.'/include/header.php'; ?>
<link rel="stylesheet" type="text/css" href="js/jquery.select2/select2.css" />
<div class="block-flat">
	<div class="content">
      <form method="post" id="addform" action="<?php echo site_url('user/create_save')?>" parsley-validate novalidate> 
      <div class="modal-body form">
		<div class="form-group">
		  <label>用户名</label> <input type="text" name="name" parsley-trigger="change" required placeholder="用户名" class="form-control">
		</div>
		<div class="form-group">
		  <label>密码</label> <input id="pass1" type="password" name="password" placeholder="密码" required class="form-control">
		</div>
		<div class="form-group">
		  <label>重输密码</label> <input parsley-equalto="#pass1" type="password" name="repeat_password" required placeholder="密码" class="form-control">
		</div>
		<div class="row">
		</div>
      </div>
      <div class="modal-footer">
      	<font color="red" class="tips" style="float: left;"></font>
		<button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">取消</button>
		<button class="btn btn-primary btn-flat" type="submit" data-dismiss="modal">添加</button>
      </div>
      </form>
    </div>
</div>
<script src="js/jquery.parsley/parsley.js" type="text/javascript"></script>
<?php require_once  VIEWPATH.'/include/footer.php'; ?>