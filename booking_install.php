<?php

/**
 *
 * @param type $root_dir
 * @return type
 */
function install_booking_plugin($root_dir, $key = '', $email = '', $domain = '') {
  chdir("$root_dir/symfony");
  $command = "php ./symfony orangehrmBookingPlugin:activate-plugin ";
  $command .= " --key=$key --email=$email --domain=$domain 2>&1";
  $output = array();
  $status = 0;
  exec($command, $output, $status);
  chdir($root_dir);
  return $output;
}

/**
 * 
 * @param type $input
 * @return string
 */
function filter_message($messages = array()) {  
  $output = '';
  foreach ($messages as $message) {
    if (!empty($message)) {
      $output .= '<p>' . trim($message," \t\n\r\0\x0B>") . '</p>';
    }
  }
  return $output;
}

$msg = '';

if (isset($_POST['accept_install'])) {
  $root_dir = __DIR__;
  $email = $_POST['license-email'];
  $key = $_POST['license-key'];
  $domain = $_POST['license-domain'];
  $shell = install_booking_plugin($root_dir, $key, $email, $domain);
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
				<form method='post' name="install-booking" id="install-booking" class="install-booking-form">
						<input type="hidden" name="accept_install" value="1">
            <div>
              <label for="license-email">License Email</label>
              <input type="text" name="license-email" value="">
            </div>
            <div>
              <label for="license-key">License Key</label>
              <input type="text" name="license-key" value="">
            </div>
            <div>
              <label for="license-domain">Domain</label>
              <input type="text" name="license-domain" value="">
            </div>
						<input type="submit" value="Install" class="submit">
				</form>
CODE;
}
?>


<html>
  <head>
    <title>OrangeHRM Booking Plugin Installer</title>
    <style>
      h1,
      h2 {
        text-align: center;
        padding-bottom: 15px;
      }

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
        background-color: #a9a9a9;
        padding: 10px 15px;
      }

      .install-booking-form {
        border: 1px solid #000;
        padding: 28px;
      }

      .install-booking-form div {
        display: block;
        width: 100%;
        margin-bottom: 16px;
      }

      .install-booking-form label {
        width: auto;
        min-width: 10%;
        max-width: 15%;
        display: inline-block;
      }

      .install-booking-form input {
        width: auto;        
        display: inline-block;
      }

      .install-booking-form .submit {
        padding: 12px 18px;
        color: #000;        
        border-radius: 8px;
        cursor: pointer;
      }

    </style>
  </head>
  <body>
    <div class="content">
      <?= $content ?>
    </div>
  </body>
</html>
