<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
 
<!-- Website Title --> 
<title>CityCRM | Login</title>

<!-- Meta data for SEO -->
<meta name="description" content="">
<meta name="keywords" content="">

<!-- Template stylesheet -->
<link href="css/screen.css" rel="stylesheet" type="text/css" media="all">
<link href="js/visualize/visualize.css" rel="stylesheet" type="text/css" media="all">

<!--[if IE]>
	<link href="css/ie.css" rel="stylesheet" type="text/css" media="all">
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
<![endif]-->

<!-- Jquery and plugins -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.img.preload.js"></script>
<script type="text/javascript" src="js/hint.js"></script>
<script type="text/javascript" src="js/visualize/jquery.visualize.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.0.js"></script>
<script type="text/javascript" charset="utf-8"> 
	$(function(){ 
    	// find all the input elements with title attributes
    	$('input[title!=""]').hint();
    	$('#login_info').click(function(){
			$(this).fadeOut('fast');
		});
	});
</script>
</head>
<body class="login">
<?php //echo Debug::vars('40', $_SESSION); ?>
	<!-- Begin login window -->
	<div id="login_wrapper">
		<br class="clear"/>
		<div id="login_top_window">
			<img src="images/top_login_window.png" alt="top window"/>
		</div>
		
		<!-- Begin content -->
		<div id="login_body_window">
			<div class="inner">
				<img src="images/login_logo2.png" alt="logo"/>
				<form action="" method="post" id="form_login" name="form_login">
					<p>
						<input type="text" id="username" name="username" style="width:285px" title="Имя пользователя"/>
					</p>
					<p>
						<input type="password" id="password" name="password" style="width:285px" title="Пароль"/>
					</p>
					<p style="margin-top:30px">
						<input type="submit" id="submit" name="submit" value="Вход" class="Login" style="margin-right:5px"/>
						<input type="hidden" name="hidden" value="form_sent" />
						<input type="checkbox" id="remember" name="remember"/>Сохранить логин и пароль
					<p style="margin-top:10px"><?php echo ConfigType::getCityCrmVer().' '
						.Arr::get(
      			Arr::get(
      					Kohana::$config->load('database')->fb,
      					'connection'
      					),
						'dsn')
						.' PHP '.phpversion();
					//	echo Debug::vars('108', Session::instance());//exit;?>
									
					</p>
					
					</p>
					
					
				</form>
				
			</div>
			
			
			
		</div>
		<!-- End content -->
		
		<div id="login_footer_window">
			<img src="images/footer_login_window.png" alt="footer window"/>
		</div>
		<div id="login_reflect">
			<img src="images/reflect.png" alt="window reflect"/>
		</div>
		
	</div>
	
	<!-- End login window -->
	
	
</body>
</html>
