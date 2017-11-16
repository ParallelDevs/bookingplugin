<?php 
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/licenseBooking.min.js'));
?>

<div class="box">
    <div class="head">
        <h1><?php echo __('License for Booking'); ?></h1>
    </div>

    <div class="inner" id="confBookingTbl">
        <?php include_partial('global/flash_messages'); ?>
        <form id="frmActivateBooking" method="post" action="<?php echo url_for('@license_booking'); ?>"
              >
            <fieldset>
                <ol>
                    <?php echo $form->render(); ?>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                <p>
                    <input type="button" class="" id="btnSend" value="<?php echo __("Submit"); ?>"  />
                </p>
            </fieldset>
        </form>
    </div>



</div> <!-- Box -->
