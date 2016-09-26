<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title?></title>
  	<base href="<?php echo base_url();?>" />
	<link rel="shortcut icon" href="images/favicon.png">
    <!-- Bootstrap core CSS -->
    <link href="js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" href="fonts/font-awesome-4/css/font-awesome.min.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="js/jquery.nanoscroller/nanoscroller.css" />
	<!-- Custom styles for this template -->
	<link rel="stylesheet" type="text/css" href="js/jquery.niftymodals/css/component.css" />
	<link rel="stylesheet" type="text/css" href="js/bootstrap.switch/bootstrap-switch.css" />
	<link rel="stylesheet" type="text/css" href="js/jquery.select2/select2.css" />
	<link href="css/style.css" rel="stylesheet" />
	
</head>
<body>

	<!-- Fixed navbar -->
	<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
		<?php require_once 'navbar.php';?>
	</div>

	<div id="cl-wrapper" class="fixed-menu sb-collapsed">
		<div class="cl-sidebar">
			<?php require_once 'sidebar.php';?>
		</div>
		<div class="container-fluid" id="pcont">
			<div class="page-head">
				<h2><?php echo $title;?></h2>
				<ol class="breadcrumb">
				  <li><a href="#"><?php echo $this->uri->segment(1);?></a></li>
				  <li class="active"><?php echo $this->uri->segment(2);?></li>
				</ol>
			</div>
			<div class="cl-mcont">