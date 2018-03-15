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
                <!--
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            	-->
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
                    	<a href="<?php echo $site['url']; ?>">
                        	<i class="fa fa-home"></i> 
                        	<span>Dashboard</span>
                        </a>
                    </li>
                    
					<?php if($_GET['c'] == 'settings'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
                    	<a href="<?php echo $site['url']; ?>?c=settings">
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
					
				// settings
				case "settings":
					settings();
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
                        <li class="active"><a href="<?php echo $site['url']; ?>">Dashboard</a></li>
                        <!-- <li class="active">Here</li> -->
                    </ol>
                </section>
    
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">ZEUS Stats</h3>
                                </div>
                                <div class="box-body">
                                    <?php
                                        include('/zeus/controller/global_vars.php');
                                        $zeus = file_get_contents('http://zeus.deltacolo.com/api/?c=home&key='.$config['api_key']);
                                        $zeus = json_decode($zeus, true);

                                        if(isset($zeus['status']))
                                        {
                                            echo '<strong>API Status:</strong> <font color="green">Online</font>' . '<br>';
                                            echo '<strong>API Version:</strong> '.$zeus['version'].''.'<br>';
                                        }else{
                                            echo '<strong>API:</strong> <font color="red">Offline</font>' . '<br>';
                                        }

                                        if($zeus['status'] == 'success')
                                        {
                                            echo '<strong>API Key:</strong> Accepted' . '<br>';
                                            echo '<strong>Site ID:</strong> '.$zeus['site']['id'].'' . '<br>';
                                            echo '<strong>Site Name:</strong> '.$zeus['site']['name'].'' . '<br>';
                                        }else{
                                            echo '<strong>Site API Key:</strong> <font color="red">Declined</font>' . '<br>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Controller Stats</h3>
                                </div>
                                <div class="box-body">
                                	<style>
            							div.chart {
            								float:left;
            								height: 250px;
            							}

            							div.full {
            								width: 100%;
            							}

            							div.half {
            								width: 50%;
            							}
            						
            						</style>
                                	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

            						<div id="chart1_div" class="chart full"></div>
            						<div id="chart2_div" class="chart half"></div>
            						<div id="chart3_div" class="chart half"></div>
                               	
                               		<script>
            							  google.charts.load('current', {'packages':['corechart']});
            							  google.charts.setOnLoadCallback(drawChart);

            							  function drawChart() {
            								var netdata = 'http://192.168.0.46:19999/api/v1/data?after=-60&format=datasource&options=nonzero&chart=';

            								// define all the charts you need
            								var mycharts = [
            									{
            									div: 'chart1_div',
            									chart: 'system.cpu',
            									type: 'stacked',
            									options: { // these are google chart options
            									  title: 'System CPU',
            									  vAxis: {minValue: 100}
            									}
            								  },
            									{
            									div: 'chart2_div',
            									chart: 'system.ram',
            									type: 'area',
            									options: { // these are google chart options
            									  title: 'System RAM',
            									}
            								  },
            									{
            									div: 'chart3_div',
            									chart: 'ipv4.tcpsock',
            									type: 'line',
            									options: { // these are google chart options
            									  title: 'TCP sockets',
            									}
            								  }
            								];

            										// initialize the google charts
            								var len = mycharts.length;
            								while(len--) {
            									mycharts[len].query = new google.visualization.Query(netdata + mycharts[len].chart, {sendMethod: 'auto'});

            								  switch(mycharts[len].type) {
            									case 'stacked':
            									  mycharts[len].options.isStacked = 'absolute';
            									  // no break here - render it as area chart
            									case 'area':
            										  mycharts[len].gchart = new google.visualization.AreaChart(document.getElementById(mycharts[len].div));
            									  break;

            									default:
            										  mycharts[len].gchart = new google.visualization.LineChart(document.getElementById(mycharts[len].div));
            										break;
            								  }
            								}

            								function refreshChart(c) {
            									c.query.send(function(data) {
            									c.gchart.draw(data.getDataTable(), c.options);
            								  });
            								}

            										setInterval(function() {
            								  var len = mycharts.length;
            								  while(len--) refreshChart(mycharts[len]);
            								}, 1000);
            							  }
                  
            						</script>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        <?php } ?>
        
        <?php  function settings(){ ?>
        	<?php global $site; ?>

        	<?php include('/zeus/controller/global_vars.php'); ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Settings <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li class="active"><a href="<?php echo $site['url']; ?>">Dashboard</a></li>
                        <li class="active">Settings</li>
                    </ol>
                </section>
    
                <section class="content">
                    <div class="row">
                    	<div class="col-md-6">
                    		<form action="actions.php?a=settings_update" method="post" class="form-horizontal">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">ZEUS API Key</h3>
									</div>
									<div class="box-body">
										<div class="form-group">
											<div class="col-sm-12">
												<input type="text" name="api_key" id="api_key" class="form-control" value="<?php echo $config['api_key']; ?>" required>
											</div>
										</div>
									</div>
									<div class="box-footer text-right">
										<button type="submit" class="btn btn-success">Save</button>
									</div>
								</div>
							</form>
						</div>
                    </div>
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
                        <li><a href="<?php echo $site['url']; ?>">Dashboard</a></li>
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