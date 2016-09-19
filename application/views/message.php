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
	<!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet" />

</head>
<body>

	<div class="content">
		<div class="cl-mcont">
			<div class="block-flat">
				<?php if($ack):?>
				<div class="alert alert-success">
					<i class="fa fa-check sign"></i><strong>成功！</strong> <?php echo $msg?>
				</div>
				<?php else:?>
				<div class="alert alert-danger">
					<i class="fa fa-times-circle sign"></i><strong>失败！</strong> <?php echo $msg?>
				 </div>
				<?php endif;?>
			</div>
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
      setTimeout(function(){ <?php if ($ack):?>parent.location.reload();<?php else:?>history.back();<?php endif;?>}, 1000);
    </script>
	<script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>