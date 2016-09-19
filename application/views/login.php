<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php echo $title;?></title>
	<base href="<?php echo base_url();?>" />
	<link rel="shortcut icon" href="images/favicon.png">
	<!-- Bootstrap core CSS -->
	<link href="js/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="fonts/font-awesome-4/css/font-awesome.min.css">
	<!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet" />
	<style>p {margin: 0;}</style>
</head>

<body class="texture">

<div id="cl-wrapper" class="login-container">
	<div class="middle-login">
		<div class="alert alert-danger alert-white rounded hidden">
			<div class="icon"><i class="fa fa-times-circle"></i></div>
			<span class="tips"></span>
		</div>
		<div class="block-flat">
			<div class="header">							
				<h3 class="text-center"><img class="logo-img" src="images/logo.png" alt="logo"/></h3>
			</div>
			<div>
				<form style="margin-bottom: 0px !important;" class="form-horizontal" action="<?php echo site_url('login/check');?>">
					<div class="content">
						<h4 class="title">用户登录</h4>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input type="text" placeholder="用户名" name="username" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" placeholder="密码" name="password" class="form-control">
									</div>
								</div>
							</div>
					</div>
					<div class="foot">
						<button class="btn btn-primary" data-dismiss="modal" type="submit">登录</button>
					</div>
				</form>
			</div>
		</div>
		<div class="text-center out-links"><a href="#">版权所有 &copy; 2016 本公司保留所有权利 | ZEN.</a></div>
	</div> 
	
</div>

<script src="js/jquery.js"></script>
<script type="text/javascript" src="js/behaviour/general.js"></script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
   	$("form").submit(function(){
   		var self = $(this);
   		if (self.find("input[name='username']").val() == ''){
   	   		alert('用户名不能为空');
   	   		return false;
   		}
   		if (self.find("input[name='password']").val() == ''){
   			alert('密码不能为空');
   	   		return false;
   		}
   		$.post(self.attr("action"), self.serialize(), success, "json");
   		return false;

   		function success(data){
   			if(data.ack){
   				window.location.href = data.url;
   			} else {
   				$(".tips").html(data.msg);
   				$(".alert").removeClass("hidden");
   			}
   		}
   	});
</script>
</body>
</html>