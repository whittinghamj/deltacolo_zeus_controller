<?php
session_start();

include('inc/global_vars.php');
include('inc/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $site['title']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    
    <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition skin-blue layout-boxed sidebar-mini">  
    <div class="wrapper">
        <header class="main-header">
            <a href="<?php echo $site['url']; ?>/dashboard" class="logo">
                <span class="logo-mini"><?php echo $site['name_short']; ?></span>
                <span class="logo-lg"><?php echo $site['name_long']; ?></span>
            </a>

            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!--
						<li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo $account_details['avatar']; ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs">
									<?php echo $account_details['firstname']; ?> <?php echo $account_details['lastname']; ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="<?php echo $account_details['avatar']; ?>" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo $account_details['firstname']; ?> <?php echo $account_details['lastname']; ?>
                                        <small><?php echo $account_details['email']; ?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!--
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo $site['url']; ?>/dashboard?c=profile" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo $site['url']; ?>/logout" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        -->
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu">
                	<?php if(empty($_GET['c']) || $_GET['c'] == '' || $_GET['c'] == 'home'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
                    	<a href="<?php echo $site['url']; ?>/dashboard">
                        	<i class="fa fa-home"></i> 
                        	<span>Dashboard</span>
                        </a>
                    </li>
                    
					<?php if($_GET['c'] == 'settings'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
                    	<a href="<?php echo $site['url']; ?>/dashboard?c=settings">
                        	<i class="fa fa-gear"></i> 
                        	<span>Settings</span>
                        </a>
                    </li>
                    
                    <!--
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#">Link in level 2</a></li>
                            <li><a href="#">Link in level 2</a></li>
                        </ul>
                    </li>
                    -->
                </ul>
            </section>
        </aside>
		
        <?php
			$c = $_GET['c'];
			switch ($c){
				// test
				case "test":
					test();
					break;
					
				// my account
				case "my_account":
					my_account();
					break;
					
				// home
				default:
					home();
					break;
			}
		?>
        
        <?php  function home(){ ?>
        	<?php global $account_details, $site; ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Dashboard <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li class="active"><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <!-- <li class="active">Here</li> -->
                    </ol>
                </section>
    
                <section class="content">
                    
                </section>
            </div>
        <?php } ?>
        
        <?php  function test(){ ?>
        	<?php global $account_details, $site; ?>
            <div class="content-wrapper">
            
            	<div id="status_message"></div>
                
                <section class="content-header">
                    <h1>Test Page <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Test Page</li>
                    </ol>
                </section>
    
                <section class="content">
                    <h4>$_GET</h4>
                    <pre>
                    	<?php debug($_GET); ?>
                    </pre>
                    <h4>$_POST</h4>
                    <pre>
                        <?php debug($_POST); ?>
                    </pre>
                    <h4>$_SESSION</h4>
                    <pre>
                        <?php debug($_SESSION); ?>
                    </pre>
                    <h4>$account_details</h4>
                    <pre>
                        <?php debug($account_details); ?>
                    </pre>
                    
                </section>
            </div>
        <?php } ?>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <!-- Anything you want -->
            </div>
            <strong>Copyright &copy; <?php echo date("Y", time()); ?> <a href="<?php echo $site['url']; ?>"><?php echo $site['title']; ?></a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
	
    <?php if(!empty($_SESSION['alert']['status'])){ ?>
    	<script>
			document.getElementById('status_message').innerHTML = '<div class="callout callout-<?php echo $_SESSION['alert']['status']; ?> lead"><p><?php echo $_SESSION['alert']['message']; ?></p></div>';
			setTimeout(function() {
				$('#status_message').fadeOut('fast');
			}, 5000);
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php } ?>
    
    <?php if($_GET['c'] == 'my_account'){ ?>
    	<script>
			$(document).on('change', '.btn-file :file', function() {
			  var input = $(this),
				  numFiles = input.get(0).files ? input.get(0).files.length : 1,
				  label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			  input.trigger('fileselect', [numFiles, label]);
			});
			
			$(document).ready( function() {
				$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
					
					var input = $(this).parents('.input-group').find(':text'),
						log = numFiles > 1 ? numFiles + ' files selected' : label;
					
					if( input.length ) {
						input.val(log);
					} else {
						if( log ) alert(log);
					}
					
				});
			});
		
			function _(el){
				return document.getElementById(el);
			}
			function uploadFile(){
				var file = _("file1").files[0];
				var uid = _("uid").value;
				// alert(file.name+" | "+file.size+" | "+file.type);
				var formdata = new FormData();
				formdata.append("file1", file);
				formdata.append("uid", uid);
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				ajax.addEventListener("error", errorHandler, false);
				ajax.addEventListener("abort", abortHandler, false);
				ajax.open("POST", "actions.php?a=my_account_update_photo");
				ajax.send(formdata);
			}
			function progressHandler(event){
				_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
				var percent = (event.loaded / event.total) * 100;
				_("progressBar").value = Math.round(percent);
				_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
			}
			function completeHandler(event){
				_("status").innerHTML = event.target.responseText;
				_("progressBar").value = 0;
				setTimeout(function() {
					set_status_message('success', 'Your profile photo has been updated.');
					window.location = window.location;
				}, 3000);
			}
			function errorHandler(event){
				_("status").innerHTML = "Upload Failed";
				setTimeout(function() {
					$('#status').fadeOut('fast');
				}, 10000);
			}
			function abortHandler(event){
				_("status").innerHTML = "Upload Aborted";
				setTimeout(function() {
					$('#status').fadeOut('fast');
				}, 10000);
			}
		</script>
    <?php } ?>
    
    <script>
		function set_status_message(status, message){
			$.ajax({
				cache: false,
				type: "GET",
				url: "actions.php?a=set_status_message&status=" + status + "&message=" + message,
				success: function(data) {
					
				}
			});	
		}
	</script>
</body>
</html>