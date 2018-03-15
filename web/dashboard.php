<?php
include('inc/db.php');
include('inc/sessions.php');
$sess = new SessionManager();
session_start();

include('inc/global_vars.php');
include('inc/functions.php');

// check is account->id is set, if not then assume user is not logged in correctly and redirect to login page
if(empty($_SESSION['account']['id'])){
	go($site['url'].'/index?c=session_timeout');
}

// get account details for logged in user
$account_details 			= account_details($_SESSION['account']['id']);

// check is logged in user has one of the correct product ids from whmcs to use this page.
$product_check 				= active_product_check( $account_details['product_ids'], $product_ids );

// if above check fails, redirect to another url
if($product_check == 0 || $product_check == ''){
	go($site['url'].'?status=error&message=No Products Found');
}
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
                                -->
                                <!-- Menu Footer-->
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
                    
					<?php if($_GET['c'] == 'my_account'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
                    	<a href="<?php echo $site['url']; ?>/dashboard?c=my_account">
                        	<i class="fa fa-gear"></i> 
                        	<span>My Account</span>
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
        
        <?php  function my_account(){ ?>
        	<?php global $account_details, $site; ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>My Account <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">My Account</li>
                    </ol>
                </section>
    
                <section class="content">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                        	<?php if(empty($_GET['tab']) || $_GET['tab']==1){ ?>
                            	<li class="active"><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=1">Profile Details</a></li>
                            <?php }else{ ?>
                            	<li><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=1">Profile Details</a></li>
                            <?php } ?>
                            
                            <?php if($_GET['tab']==2){ ?>
                            	<li class="active"><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=2">Profile Photo</a></li>
                            <?php }else{ ?>
                            	<li><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=2">Profile Photo</a></li>
                            <?php } ?>
                            
                            <?php if($_GET['tab']==3){ ?>
                            	<li class="active"><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=3">My Products</a></li>
                            <?php }else{ ?>
                            	<li><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=3">My Products</a></li>
                            <?php } ?>
                            
                        </ul>
                        <div class="tab-content">
                            <?php if(empty($_GET['tab']) || $_GET['tab']==1){ ?>
                            	<div class="active tab-pane" id="profile_settings">
                            <?php }else{ ?>
                            	<div class="tab-pane" id="profile_settings">
                            <?php } ?>
                                <form action="actions.php?a=my_account_update" method="post" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="firstname" class="col-sm-2 control-label">Firstname</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $account_details['firstname']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="lastname" class="col-sm-2 control-label">Lastname</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $account_details['lastname']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="address1" class="col-sm-2 control-label">Address</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="address1" id="address1" class="form-control" value="<?php echo $account_details['address1']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="address2" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="address2" id="address2" class="form-control" value="<?php echo $account_details['address2']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="city" class="col-sm-2 control-label">City</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="city" id="city" class="form-control" value="<?php echo $account_details['city']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="state" class="col-sm-2 control-label">State</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="state" id="state" class="form-control" value="<?php echo $account_details['state']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="postcode" class="col-sm-2 control-label">Postcode</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="postcode" id="postcode" class="form-control" value="<?php echo $account_details['postcode']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="country" class="col-sm-2 control-label">Country</label>
                                        <div class="col-sm-10">
                                            <select name="country" id="country" class="form-control">
                                                <option value="AF" <?php if($account_details['country']=='AF'){echo"selected";} ?>>Afghanistan</option>
                                                <option value="AX" <?php if($account_details['country']=='AX'){echo"selected";} ?>>Åland Islands</option>
                                                <option value="AL" <?php if($account_details['country']=='AL'){echo"selected";} ?>>Albania</option>
                                                <option value="DZ" <?php if($account_details['country']=='DZ'){echo"selected";} ?>>Algeria</option>
                                                <option value="AS" <?php if($account_details['country']=='AS'){echo"selected";} ?>>American Samoa</option>
                                                <option value="AD" <?php if($account_details['country']=='AD'){echo"selected";} ?>>Andorra</option>
                                                <option value="AO" <?php if($account_details['country']=='AO'){echo"selected";} ?>>Angola</option>
                                                <option value="AI" <?php if($account_details['country']=='AI'){echo"selected";} ?>>Anguilla</option>
                                                <option value="AQ" <?php if($account_details['country']=='AQ'){echo"selected";} ?>>Antarctica</option>
                                                <option value="AG" <?php if($account_details['country']=='AG'){echo"selected";} ?>>Antigua and Barbuda</option>
                                                <option value="AR" <?php if($account_details['country']=='AR'){echo"selected";} ?>>Argentina</option>
                                                <option value="AM" <?php if($account_details['country']=='AM'){echo"selected";} ?>>Armenia</option>
                                                <option value="AW" <?php if($account_details['country']=='AN'){echo"selected";} ?>>Aruba</option>
                                                <option value="AU" <?php if($account_details['country']=='AU'){echo"selected";} ?>>Australia</option>
                                                <option value="AT" <?php if($account_details['country']=='AT'){echo"selected";} ?>>Austria</option>
                                                <option value="AZ" <?php if($account_details['country']=='AZ'){echo"selected";} ?>>Azerbaijan</option>
                                                <option value="BS" <?php if($account_details['country']=='BS'){echo"selected";} ?>>Bahamas</option>
                                                <option value="BH" <?php if($account_details['country']=='BH'){echo"selected";} ?>>Bahrain</option>
                                                <option value="BD" <?php if($account_details['country']=='BD'){echo"selected";} ?>>Bangladesh</option>
                                                <option value="BB" <?php if($account_details['country']=='BB'){echo"selected";} ?>>Barbados</option>
                                                <option value="BY" <?php if($account_details['country']=='BY'){echo"selected";} ?>>Belarus</option>
                                                <option value="BE" <?php if($account_details['country']=='BE'){echo"selected";} ?>>Belgium</option>
                                                <option value="BZ" <?php if($account_details['country']=='BZ'){echo"selected";} ?>>Belize</option>
                                                <option value="BJ" <?php if($account_details['country']=='BJ'){echo"selected";} ?>>Benin</option>
                                                <option value="BM" <?php if($account_details['country']=='BM'){echo"selected";} ?>>Bermuda</option>
                                                <option value="BT" <?php if($account_details['country']=='BT'){echo"selected";} ?>>Bhutan</option>
                                                <option value="BO" <?php if($account_details['country']=='BO'){echo"selected";} ?>>Bolivia, Plurinational State of</option>
                                                <option value="BQ" <?php if($account_details['country']=='BQ'){echo"selected";} ?>>Bonaire, Sint Eustatius and Saba</option>
                                                <option value="BA" <?php if($account_details['country']=='BA'){echo"selected";} ?>>Bosnia and Herzegovina</option>
                                                <option value="BW" <?php if($account_details['country']=='BW'){echo"selected";} ?>>Botswana</option>
                                                <option value="BV" <?php if($account_details['country']=='BV'){echo"selected";} ?>>Bouvet Island</option>
                                                <option value="BR" <?php if($account_details['country']=='BR'){echo"selected";} ?>>Brazil</option>
                                                <option value="IO" <?php if($account_details['country']=='IO'){echo"selected";} ?>>British Indian Ocean Territory</option>
                                                <option value="BN" <?php if($account_details['country']=='BN'){echo"selected";} ?>>Brunei Darussalam</option>
                                                <option value="BG" <?php if($account_details['country']=='BG'){echo"selected";} ?>>Bulgaria</option>
                                                <option value="BF" <?php if($account_details['country']=='BF'){echo"selected";} ?>>Burkina Faso</option>
                                                <option value="BI" <?php if($account_details['country']=='BI'){echo"selected";} ?>>Burundi</option>
                                                <option value="KH" <?php if($account_details['country']=='KH'){echo"selected";} ?>>Cambodia</option>
                                                <option value="CM" <?php if($account_details['country']=='CM'){echo"selected";} ?>>Cameroon</option>
                                                <option value="CA" <?php if($account_details['country']=='CA'){echo"selected";} ?>>Canada</option>
                                                <option value="CV" <?php if($account_details['country']=='CV'){echo"selected";} ?>>Cape Verde</option>
                                                <option value="KY" <?php if($account_details['country']=='KY'){echo"selected";} ?>>Cayman Islands</option>
                                                <option value="CF" <?php if($account_details['country']=='CF'){echo"selected";} ?>>Central African Republic</option>
                                                <option value="TD" <?php if($account_details['country']=='TD'){echo"selected";} ?>>Chad</option>
                                                <option value="CL" <?php if($account_details['country']=='CL'){echo"selected";} ?>>Chile</option>
                                                <option value="CN" <?php if($account_details['country']=='CN'){echo"selected";} ?>>China</option>
                                                <option value="CX" <?php if($account_details['country']=='CX'){echo"selected";} ?>>Christmas Island</option>
                                                <option value="CC" <?php if($account_details['country']=='CC'){echo"selected";} ?>>Cocos (Keeling) Islands</option>
                                                <option value="CO" <?php if($account_details['country']=='CO'){echo"selected";} ?>>Colombia</option>
                                                <option value="KM" <?php if($account_details['country']=='KM'){echo"selected";} ?>>Comoros</option>
                                                <option value="CG" <?php if($account_details['country']=='CG'){echo"selected";} ?>>Congo</option>
                                                <option value="CD" <?php if($account_details['country']=='CD'){echo"selected";} ?>>Congo, the Democratic Republic of the</option>
                                                <option value="CK" <?php if($account_details['country']=='CK'){echo"selected";} ?>>Cook Islands</option>
                                                <option value="CR" <?php if($account_details['country']=='CR'){echo"selected";} ?>>Costa Rica</option>
                                                <option value="CI" <?php if($account_details['country']=='CI'){echo"selected";} ?>>Côte d'Ivoire</option>
                                                <option value="HR" <?php if($account_details['country']=='HR'){echo"selected";} ?>>Croatia</option>
                                                <option value="CU" <?php if($account_details['country']=='CU'){echo"selected";} ?>>Cuba</option>
                                                <option value="CW" <?php if($account_details['country']=='CW'){echo"selected";} ?>>Curaçao</option>
                                                <option value="CY" <?php if($account_details['country']=='CY'){echo"selected";} ?>>Cyprus</option>
                                                <option value="CZ" <?php if($account_details['country']=='CZ'){echo"selected";} ?>>Czech Republic</option>
                                                <option value="DK" <?php if($account_details['country']=='DK'){echo"selected";} ?>>Denmark</option>
                                                <option value="DJ" <?php if($account_details['country']=='DJ'){echo"selected";} ?>>Djibouti</option>
                                                <option value="DM" <?php if($account_details['country']=='DM'){echo"selected";} ?>>Dominica</option>
                                                <option value="DO" <?php if($account_details['country']=='DO'){echo"selected";} ?>>Dominican Republic</option>
                                                <option value="EC" <?php if($account_details['country']=='EC'){echo"selected";} ?>>Ecuador</option>
                                                <option value="EG" <?php if($account_details['country']=='EG'){echo"selected";} ?>>Egypt</option>
                                                <option value="SV" <?php if($account_details['country']=='SV'){echo"selected";} ?>>El Salvador</option>
                                                <option value="GQ" <?php if($account_details['country']=='GQ'){echo"selected";} ?>>Equatorial Guinea</option>
                                                <option value="ER" <?php if($account_details['country']=='ER'){echo"selected";} ?>>Eritrea</option>
                                                <option value="EE" <?php if($account_details['country']=='EE'){echo"selected";} ?>>Estonia</option>
                                                <option value="ET" <?php if($account_details['country']=='ET'){echo"selected";} ?>>Ethiopia</option>
                                                <option value="FK" <?php if($account_details['country']=='FK'){echo"selected";} ?>>Falkland Islands (Malvinas)</option>
                                                <option value="FO" <?php if($account_details['country']=='DO'){echo"selected";} ?>>Faroe Islands</option>
                                                <option value="FJ" <?php if($account_details['country']=='FJ'){echo"selected";} ?>>Fiji</option>
                                                <option value="FI" <?php if($account_details['country']=='FI'){echo"selected";} ?>>Finland</option>
                                                <option value="FR" <?php if($account_details['country']=='FR'){echo"selected";} ?>>France</option>
                                                <option value="GF" <?php if($account_details['country']=='GF'){echo"selected";} ?>>French Guiana</option>
                                                <option value="PF" <?php if($account_details['country']=='PF'){echo"selected";} ?>>French Polynesia</option>
                                                <option value="TF" <?php if($account_details['country']=='TF'){echo"selected";} ?>>French Southern Territories</option>
                                                <option value="GA" <?php if($account_details['country']=='GA'){echo"selected";} ?>>Gabon</option>
                                                <option value="GM" <?php if($account_details['country']=='GM'){echo"selected";} ?>>Gambia</option>
                                                <option value="GE" <?php if($account_details['country']=='GE'){echo"selected";} ?>>Georgia</option>
                                                <option value="DE" <?php if($account_details['country']=='DE'){echo"selected";} ?>>Germany</option>
                                                <option value="GH" <?php if($account_details['country']=='GH'){echo"selected";} ?>>Ghana</option>
                                                <option value="GI" <?php if($account_details['country']=='GI'){echo"selected";} ?>>Gibraltar</option>
                                                <option value="GR" <?php if($account_details['country']=='GR'){echo"selected";} ?>>Greece</option>
                                                <option value="GL" <?php if($account_details['country']=='GL'){echo"selected";} ?>>Greenland</option>
                                                <option value="GD" <?php if($account_details['country']=='GD'){echo"selected";} ?>>Grenada</option>
                                                <option value="GP" <?php if($account_details['country']=='GP'){echo"selected";} ?>>Guadeloupe</option>
                                                <option value="GU" <?php if($account_details['country']=='GU'){echo"selected";} ?>>Guam</option>
                                                <option value="GT" <?php if($account_details['country']=='GT'){echo"selected";} ?>>Guatemala</option>
                                                <option value="GG" <?php if($account_details['country']=='GG'){echo"selected";} ?>>Guernsey</option>
                                                <option value="GN" <?php if($account_details['country']=='GN'){echo"selected";} ?>>Guinea</option>
                                                <option value="GW" <?php if($account_details['country']=='GW'){echo"selected";} ?>>Guinea-Bissau</option>
                                                <option value="GY" <?php if($account_details['country']=='GY'){echo"selected";} ?>>Guyana</option>
                                                <option value="HT" <?php if($account_details['country']=='HT'){echo"selected";} ?>>Haiti</option>
                                                <option value="HM" <?php if($account_details['country']=='HM'){echo"selected";} ?>>Heard Island and McDonald Islands</option>
                                                <option value="VA" <?php if($account_details['country']=='VA'){echo"selected";} ?>>Holy See (Vatican City State)</option>
                                                <option value="HN" <?php if($account_details['country']=='HN'){echo"selected";} ?>>Honduras</option>
                                                <option value="HK" <?php if($account_details['country']=='HK'){echo"selected";} ?>>Hong Kong</option>
                                                <option value="HU" <?php if($account_details['country']=='HU'){echo"selected";} ?>>Hungary</option>
                                                <option value="IS" <?php if($account_details['country']=='IS'){echo"selected";} ?>>Iceland</option>
                                                <option value="IN" <?php if($account_details['country']=='IN'){echo"selected";} ?>>India</option>
                                                <option value="ID" <?php if($account_details['country']=='ID'){echo"selected";} ?>>Indonesia</option>
                                                <option value="IR" <?php if($account_details['country']=='IR'){echo"selected";} ?>>Iran, Islamic Republic of</option>
                                                <option value="IQ" <?php if($account_details['country']=='IQ'){echo"selected";} ?>>Iraq</option>
                                                <option value="IE" <?php if($account_details['country']=='IE'){echo"selected";} ?>>Ireland</option>
                                                <option value="IM" <?php if($account_details['country']=='IM'){echo"selected";} ?>>Isle of Man</option>
                                                <option value="IL" <?php if($account_details['country']=='IL'){echo"selected";} ?>>Israel</option>
                                                <option value="IT" <?php if($account_details['country']=='IT'){echo"selected";} ?>>Italy</option>
                                                <option value="JM" <?php if($account_details['country']=='JM'){echo"selected";} ?>>Jamaica</option>
                                                <option value="JP" <?php if($account_details['country']=='JP'){echo"selected";} ?>>Japan</option>
                                                <option value="JE" <?php if($account_details['country']=='HE'){echo"selected";} ?>>Jersey</option>
                                                <option value="JO" <?php if($account_details['country']=='JO'){echo"selected";} ?>>Jordan</option>
                                                <option value="KZ" <?php if($account_details['country']=='KZ'){echo"selected";} ?>>Kazakhstan</option>
                                                <option value="KE" <?php if($account_details['country']=='KE'){echo"selected";} ?>>Kenya</option>
                                                <option value="KI" <?php if($account_details['country']=='KI'){echo"selected";} ?>>Kiribati</option>
                                                <option value="KP" <?php if($account_details['country']=='KP'){echo"selected";} ?>>Korea, Democratic People's Republic of</option>
                                                <option value="KR" <?php if($account_details['country']=='KR'){echo"selected";} ?>>Korea, Republic of</option>
                                                <option value="KW" <?php if($account_details['country']=='KW'){echo"selected";} ?>>Kuwait</option>
                                                <option value="KG" <?php if($account_details['country']=='KG'){echo"selected";} ?>>Kyrgyzstan</option>
                                                <option value="LA" <?php if($account_details['country']=='LA'){echo"selected";} ?>>Lao People's Democratic Republic</option>
                                                <option value="LV" <?php if($account_details['country']=='LV'){echo"selected";} ?>>Latvia</option>
                                                <option value="LB" <?php if($account_details['country']=='LB'){echo"selected";} ?>>Lebanon</option>
                                                <option value="LS" <?php if($account_details['country']=='LS'){echo"selected";} ?>>Lesotho</option>
                                                <option value="LR" <?php if($account_details['country']=='LR'){echo"selected";} ?>>Liberia</option>
                                                <option value="LY" <?php if($account_details['country']=='LY'){echo"selected";} ?>>Libya</option>
                                                <option value="LI" <?php if($account_details['country']=='LI'){echo"selected";} ?>>Liechtenstein</option>
                                                <option value="LT" <?php if($account_details['country']=='LT'){echo"selected";} ?>>Lithuania</option>
                                                <option value="LU" <?php if($account_details['country']=='LU'){echo"selected";} ?>>Luxembourg</option>
                                                <option value="MO" <?php if($account_details['country']=='MO'){echo"selected";} ?>>Macao</option>
                                                <option value="MK" <?php if($account_details['country']=='MK'){echo"selected";} ?>>Macedonia, the former Yugoslav Republic of</option>
                                                <option value="MG" <?php if($account_details['country']=='MG'){echo"selected";} ?>>Madagascar</option>
                                                <option value="MW" <?php if($account_details['country']=='MW'){echo"selected";} ?>>Malawi</option>
                                                <option value="MY" <?php if($account_details['country']=='MY'){echo"selected";} ?>>Malaysia</option>
                                                <option value="MV" <?php if($account_details['country']=='MV'){echo"selected";} ?>>Maldives</option>
                                                <option value="ML" <?php if($account_details['country']=='ML'){echo"selected";} ?>>Mali</option>
                                                <option value="MT" <?php if($account_details['country']=='MT'){echo"selected";} ?>>Malta</option>
                                                <option value="MH" <?php if($account_details['country']=='MH'){echo"selected";} ?>>Marshall Islands</option>
                                                <option value="MQ" <?php if($account_details['country']=='MQ'){echo"selected";} ?>>Martinique</option>
                                                <option value="MR" <?php if($account_details['country']=='MR'){echo"selected";} ?>>Mauritania</option>
                                                <option value="MU" <?php if($account_details['country']=='MU'){echo"selected";} ?>>Mauritius</option>
                                                <option value="YT" <?php if($account_details['country']=='YT'){echo"selected";} ?>>Mayotte</option>
                                                <option value="MX" <?php if($account_details['country']=='MX'){echo"selected";} ?>>Mexico</option>
                                                <option value="FM" <?php if($account_details['country']=='FM'){echo"selected";} ?>>Micronesia, Federated States of</option>
                                                <option value="MD" <?php if($account_details['country']=='MD'){echo"selected";} ?>>Moldova, Republic of</option>
                                                <option value="MC" <?php if($account_details['country']=='MC'){echo"selected";} ?>>Monaco</option>
                                                <option value="MN" <?php if($account_details['country']=='MN'){echo"selected";} ?>>Mongolia</option>
                                                <option value="ME" <?php if($account_details['country']=='ME'){echo"selected";} ?>>Montenegro</option>
                                                <option value="MS" <?php if($account_details['country']=='MS'){echo"selected";} ?>>Montserrat</option>
                                                <option value="MA" <?php if($account_details['country']=='MA'){echo"selected";} ?>>Morocco</option>
                                                <option value="MZ" <?php if($account_details['country']=='MZ'){echo"selected";} ?>>Mozambique</option>
                                                <option value="MM" <?php if($account_details['country']=='MM'){echo"selected";} ?>>Myanmar</option>
                                                <option value="NA" <?php if($account_details['country']=='NA'){echo"selected";} ?>>Namibia</option>
                                                <option value="NR" <?php if($account_details['country']=='NR'){echo"selected";} ?>>Nauru</option>
                                                <option value="NP" <?php if($account_details['country']=='NP'){echo"selected";} ?>>Nepal</option>
                                                <option value="NL" <?php if($account_details['country']=='NL'){echo"selected";} ?>>Netherlands</option>
                                                <option value="NC" <?php if($account_details['country']=='NC'){echo"selected";} ?>>New Caledonia</option>
                                                <option value="NZ" <?php if($account_details['country']=='NZ'){echo"selected";} ?>>New Zealand</option>
                                                <option value="NI" <?php if($account_details['country']=='NI'){echo"selected";} ?>>Nicaragua</option>
                                                <option value="NE" <?php if($account_details['country']=='NE'){echo"selected";} ?>>Niger</option>
                                                <option value="NG" <?php if($account_details['country']=='NG'){echo"selected";} ?>>Nigeria</option>
                                                <option value="NU" <?php if($account_details['country']=='NU'){echo"selected";} ?>>Niue</option>
                                                <option value="NF" <?php if($account_details['country']=='NG'){echo"selected";} ?>>Norfolk Island</option>
                                                <option value="MP" <?php if($account_details['country']=='MO'){echo"selected";} ?>>Northern Mariana Islands</option>
                                                <option value="NO" <?php if($account_details['country']=='NO'){echo"selected";} ?>>Norway</option>
                                                <option value="OM" <?php if($account_details['country']=='OM'){echo"selected";} ?>>Oman</option>
                                                <option value="PK" <?php if($account_details['country']=='PK'){echo"selected";} ?>>Pakistan</option>
                                                <option value="PW" <?php if($account_details['country']=='PW'){echo"selected";} ?>>Palau</option>
                                                <option value="PS" <?php if($account_details['country']=='PS'){echo"selected";} ?>>Palestinian Territory, Occupied</option>
                                                <option value="PA" <?php if($account_details['country']=='PA'){echo"selected";} ?>>Panama</option>
                                                <option value="PG" <?php if($account_details['country']=='PG'){echo"selected";} ?>>Papua New Guinea</option>
                                                <option value="PY" <?php if($account_details['country']=='PY'){echo"selected";} ?>>Paraguay</option>
                                                <option value="PE" <?php if($account_details['country']=='PE'){echo"selected";} ?>>Peru</option>
                                                <option value="PH" <?php if($account_details['country']=='PH'){echo"selected";} ?>>Philippines</option>
                                                <option value="PN" <?php if($account_details['country']=='PN'){echo"selected";} ?>>Pitcairn</option>
                                                <option value="PL" <?php if($account_details['country']=='PL'){echo"selected";} ?>>Poland</option>
                                                <option value="PT" <?php if($account_details['country']=='PT'){echo"selected";} ?>>Portugal</option>
                                                <option value="PR" <?php if($account_details['country']=='PR'){echo"selected";} ?>>Puerto Rico</option>
                                                <option value="QA" <?php if($account_details['country']=='QA'){echo"selected";} ?>>Qatar</option>
                                                <option value="RE" <?php if($account_details['country']=='RE'){echo"selected";} ?>>Réunion</option>
                                                <option value="RO" <?php if($account_details['country']=='RO'){echo"selected";} ?>>Romania</option>
                                                <option value="RU" <?php if($account_details['country']=='RU'){echo"selected";} ?>>Russian Federation</option>
                                                <option value="RW" <?php if($account_details['country']=='RW'){echo"selected";} ?>>Rwanda</option>
                                                <option value="BL" <?php if($account_details['country']=='BL'){echo"selected";} ?>>Saint Barthélemy</option>
                                                <option value="SH" <?php if($account_details['country']=='SH'){echo"selected";} ?>>Saint Helena, Ascension and Tristan da Cunha</option>
                                                <option value="KN" <?php if($account_details['country']=='KN'){echo"selected";} ?>>Saint Kitts and Nevis</option>
                                                <option value="LC" <?php if($account_details['country']=='LC'){echo"selected";} ?>>Saint Lucia</option>
                                                <option value="MF" <?php if($account_details['country']=='MF'){echo"selected";} ?>>Saint Martin (French part)</option>
                                                <option value="PM" <?php if($account_details['country']=='PM'){echo"selected";} ?>>Saint Pierre and Miquelon</option>
                                                <option value="VC" <?php if($account_details['country']=='VC'){echo"selected";} ?>>Saint Vincent and the Grenadines</option>
                                                <option value="WS" <?php if($account_details['country']=='WS'){echo"selected";} ?>>Samoa</option>
                                                <option value="SM" <?php if($account_details['country']=='SM'){echo"selected";} ?>>San Marino</option>
                                                <option value="ST" <?php if($account_details['country']=='ST'){echo"selected";} ?>>Sao Tome and Principe</option>
                                                <option value="SA" <?php if($account_details['country']=='SA'){echo"selected";} ?>>Saudi Arabia</option>
                                                <option value="SN" <?php if($account_details['country']=='SN'){echo"selected";} ?>>Senegal</option>
                                                <option value="RS" <?php if($account_details['country']=='RS'){echo"selected";} ?>>Serbia</option>
                                                <option value="SC" <?php if($account_details['country']=='SC'){echo"selected";} ?>>Seychelles</option>
                                                <option value="SL" <?php if($account_details['country']=='SL'){echo"selected";} ?>>Sierra Leone</option>
                                                <option value="SG" <?php if($account_details['country']=='SG'){echo"selected";} ?>>Singapore</option>
                                                <option value="SX" <?php if($account_details['country']=='SX'){echo"selected";} ?>>Sint Maarten (Dutch part)</option>
                                                <option value="SK" <?php if($account_details['country']=='SK'){echo"selected";} ?>>Slovakia</option>
                                                <option value="SI" <?php if($account_details['country']=='SI'){echo"selected";} ?>>Slovenia</option>
                                                <option value="SB" <?php if($account_details['country']=='SB'){echo"selected";} ?>>Solomon Islands</option>
                                                <option value="SO" <?php if($account_details['country']=='SO'){echo"selected";} ?>>Somalia</option>
                                                <option value="ZA" <?php if($account_details['country']=='ZA'){echo"selected";} ?>>South Africa</option>
                                                <option value="GS" <?php if($account_details['country']=='GS'){echo"selected";} ?>>South Georgia and the South Sandwich Islands</option>
                                                <option value="SS" <?php if($account_details['country']=='SS'){echo"selected";} ?>>South Sudan</option>
                                                <option value="ES" <?php if($account_details['country']=='ES'){echo"selected";} ?>>Spain</option>
                                                <option value="LK" <?php if($account_details['country']=='LK'){echo"selected";} ?>>Sri Lanka</option>
                                                <option value="SD" <?php if($account_details['country']=='SD'){echo"selected";} ?>>Sudan</option>
                                                <option value="SR" <?php if($account_details['country']=='SR'){echo"selected";} ?>>Suriname</option>
                                                <option value="SJ" <?php if($account_details['country']=='SJ'){echo"selected";} ?>>Svalbard and Jan Mayen</option>
                                                <option value="SZ" <?php if($account_details['country']=='SZ'){echo"selected";} ?>>Swaziland</option>
                                                <option value="SE" <?php if($account_details['country']=='SE'){echo"selected";} ?>>Sweden</option>
                                                <option value="CH" <?php if($account_details['country']=='CH'){echo"selected";} ?>>Switzerland</option>
                                                <option value="SY" <?php if($account_details['country']=='SY'){echo"selected";} ?>>Syrian Arab Republic</option>
                                                <option value="TW" <?php if($account_details['country']=='TW'){echo"selected";} ?>>Taiwan, Province of China</option>
                                                <option value="TJ" <?php if($account_details['country']=='TJ'){echo"selected";} ?>>Tajikistan</option>
                                                <option value="TZ" <?php if($account_details['country']=='TZ'){echo"selected";} ?>>Tanzania, United Republic of</option>
                                                <option value="TH" <?php if($account_details['country']=='TH'){echo"selected";} ?>>Thailand</option>
                                                <option value="TL" <?php if($account_details['country']=='TL'){echo"selected";} ?>>Timor-Leste</option>
                                                <option value="TG" <?php if($account_details['country']=='TG'){echo"selected";} ?>>Togo</option>
                                                <option value="TK" <?php if($account_details['country']=='TK'){echo"selected";} ?>>Tokelau</option>
                                                <option value="TO" <?php if($account_details['country']=='TO'){echo"selected";} ?>>Tonga</option>
                                                <option value="TT" <?php if($account_details['country']=='TT'){echo"selected";} ?>>Trinidad and Tobago</option>
                                                <option value="TN" <?php if($account_details['country']=='TN'){echo"selected";} ?>>Tunisia</option>
                                                <option value="TR" <?php if($account_details['country']=='TR'){echo"selected";} ?>>Turkey</option>
                                                <option value="TM" <?php if($account_details['country']=='TM'){echo"selected";} ?>>Turkmenistan</option>
                                                <option value="TC" <?php if($account_details['country']=='TC'){echo"selected";} ?>>Turks and Caicos Islands</option>
                                                <option value="TV" <?php if($account_details['country']=='TV'){echo"selected";} ?>>Tuvalu</option>
                                                <option value="UG" <?php if($account_details['country']=='UG'){echo"selected";} ?>>Uganda</option>
                                                <option value="UA" <?php if($account_details['country']=='UA'){echo"selected";} ?>>Ukraine</option>
                                                <option value="AE" <?php if($account_details['country']=='AE'){echo"selected";} ?>>United Arab Emirates</option>
                                                <option value="GB" <?php if($account_details['country']=='GB'){echo"selected";} ?>>United Kingdom</option>
                                                <option value="US" <?php if($account_details['country']=='US'){echo"selected";} ?>>United States</option>
                                                <option value="UM" <?php if($account_details['country']=='UM'){echo"selected";} ?>>United States Minor Outlying Islands</option>
                                                <option value="UY" <?php if($account_details['country']=='UY'){echo"selected";} ?>>Uruguay</option>
                                                <option value="UZ" <?php if($account_details['country']=='UZ'){echo"selected";} ?>>Uzbekistan</option>
                                                <option value="VU" <?php if($account_details['country']=='VU'){echo"selected";} ?>>Vanuatu</option>
                                                <option value="VE" <?php if($account_details['country']=='VE'){echo"selected";} ?>>Venezuela, Bolivarian Republic of</option>
                                                <option value="VN" <?php if($account_details['country']=='VN'){echo"selected";} ?>>Viet Nam</option>
                                                <option value="VG" <?php if($account_details['country']=='VG'){echo"selected";} ?>>Virgin Islands, British</option>
                                                <option value="VI" <?php if($account_details['country']=='VI'){echo"selected";} ?>>Virgin Islands, U.S.</option>
                                                <option value="WF" <?php if($account_details['country']=='WF'){echo"selected";} ?>>Wallis and Futuna</option>
                                                <option value="EH" <?php if($account_details['country']=='EH'){echo"selected";} ?>>Western Sahara</option>
                                                <option value="YE" <?php if($account_details['country']=='YE'){echo"selected";} ?>>Yemen</option>
                                                <option value="ZM" <?php if($account_details['country']=='ZM'){echo"selected";} ?>>Zambia</option>
                                                <option value="ZW" <?php if($account_details['country']=='ZW'){echo"selected";} ?>>Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="form-group">
                                        <label for="email" class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" id="email" class="form-control" value="<?php echo $account_details['email']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="phonenumber" class="col-sm-2 control-label">Tel</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="phonenumber" id="phonenumber" class="form-control" value="<?php echo $account_details['phonenumber']; ?>">
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <?php if($_GET['tab']==2){ ?>
                            	<div class="active tab-pane" id="profile_avatar">
                            <?php }else{ ?>
                            	<div class="tab-pane" id="profile_avatar">
                            <?php } ?>
                                <form name="upload_form" id="upload_form" enctype="multipart/form-data" method="post">
                                To upload a profile photo, simple select the file you wish to upload and click the upload button.<br><br>
                                    <input type="hidden" name="uid" id="uid" value="<?php echo $account_details['account']['id']; ?>">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="col-lg-6 col-sm-6 col-12">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <span class="btn btn-primary btn-file">
                                                                Browse&hellip; <input type="file" name="file1" id="file1" accept="image/*">
                                                            </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly>
                                                    </div>
                                                    <br>
                                                    <center>
                                                        <progress id="progressBar" value="0" max="100" style="width:100%;"></progress>
                                                        <span id="loaded_n_total"></span> <span id="status"></span>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="row">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="button" class="btn btn-success" value="Upload File" onclick="uploadFile()">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <?php if($_GET['tab']==3){ ?>
                            	<div class="active tab-pane" id="my_products">
                            <?php }else{ ?>
                            	<div class="tab-pane" id="my_products">
                            <?php } ?>
								<div class="box">
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Product</th>
                                                <th>Status</th>
                                            </tr>
                                            <?php show_my_profile_products($account_details); ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
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