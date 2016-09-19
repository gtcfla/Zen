<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ZEN - 提示信息</title>
  	<base href="<?php echo base_url();?>" />
	<link rel="shortcut icon" href="images/favicon.png">
    <!-- Bootstrap core CSS -->
    <link href="js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" href="fonts/font-awesome-4/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="js/jquery.niftymodals/css/component.css" />
	<!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet" />

</head>
<body>

	<div class="content">
		<div class="cl-mcont">
			<div class="md-modal colored-header <?php echo $ack ? 'success' : 'danger'?> md-effect-12 md-show">
			<div class="md-content">
			<div class="modal-header">
			<h3>跳转提示信息框</h3>
			<button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<div class="i-circle <?php echo $ack ? 'success' : 'danger'?>"><i class="fa fa-<?php echo $ack ? 'check' : 'times'?>"></i></div>
					<h4><?php echo $message; ?></h4>
					<p>正在返回..</p>
					<p><a href="<?php echo $url; ?>">如果您的浏览器没有自动跳转，请点击这里</a></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat" onClick="javascript:history.go(-1);"><i class="fa fa-caret-left"></i>返回上级</button>
				<a href="<?php echo site_url('welcome/index')?>" class="btn btn-<?php echo $ack ? 'success' : 'danger'?>">返回主页 <i class="fa fa-home fw"></i></a>
			</div>
			</div>
			</div>
			<div class="md-overlay"></div>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/behaviour/general.js"></script>
  	<!-- Bootstrap core JavaScript -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript">
      $(document).ready(function(){
        //initialize the javascript
        App.init();
      });
      setTimeout("window.location.href ='<?php echo $url; ?>';", <?php echo 2000; ?>);
    </script>
	<script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>
