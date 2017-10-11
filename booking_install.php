<?php

/**
 *
 * @param type $root_dir
 * @return type
 */
function install_booking_plugin($root_dir) {
  chdir("$root_dir/symfony");
  $command = "php ./symfony orangehrm:install-plugin orangehrmBookingPlugin";
  $output = shell_exec($command);
  chdir($root_dir);
  return $output;
}

/**
 * 
 * @param type $input
 * @return string
 */
function filter_message($input = '') {
  $messages = explode('>>', $input);
  $output = '';
  foreach ($messages as $message) {
    if(!empty($message)){
      $output.='<p>'.$message.'</p>';
    }
  }
  return $output;
}

$msg = '';

if (isset($_POST['accept_install'])) {
  $root_dir = __DIR__;
  $shell = install_booking_plugin($root_dir);  
  $msg .= filter_message($shell);
  $msg .= "<br/>\n";
  $template = <<< CODE
<h1>Booking Plugin Installer for OrangeHRM</h1>

				<div>
						<h2>Installation Logs</h2>
						<div class="logs">--result--</div>
				</div>
				<p>In case of error, contact your site administrator.</p>
				<p>If not, you have installed Booking Plugin in your OrangeHRM site.</p>
        <p>Please login for refreshing the navigation menu.</p>
        <p> <a href="index.php">Index</a>.</p>
          
CODE;

  $content = str_replace('--result--', $msg, $template);
}
else {
  $content = <<< CODE
<h1>Welcome to Booking Plugin Installer for OrangeHRM</h1>
				<p>You are about to install the Booking Plugin, please make sure that you have made all necessary backups of your site.</p>
				<p>Click on the "Install" button to proceed.</p>
				<form method='post' name="install-booking" id="install-booking">
						<input type="hidden" name="accept_install" value="1">
						<input type="submit" value="Install">
				</form>
CODE;
}
?>


<html>
  <head>
    <title>OrangeHRM Booking Plugin Installer</title>
    <style>
      h1, h2 {
        text-align: center;
        padding-bottom: 15px;
      };
      p {
        text-align: justify;
        line-height: 1.5;
      }
      .content {
        float: left;
        margin: auto 5%;
        width: 90%;
      }
      .logs {
        width: 100%;
        font-weight: bolder;
        text-align: left;
        text-transform: uppercase;
        background-color: #A9A9A9;
        padding: 10px 15px;
      }
    </style>
  </head>
  <body>
    <div class="content">
      <?= $content ?>
    </div>
  </body>
</html>
