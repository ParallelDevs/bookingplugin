<?php 
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/configureBooking.js'));
?>
<div class="box">


    <div class="head">
        <h1><?php echo __('Configure Booking'); ?></h1>
    </div>

    <div class="inner" id="confBookingTbl">
        <?php include_partial('global/flash_messages'); ?>
        <form id="frmConfigBooking" method="post" action="<?php echo url_for('@configure_booking'); ?>"
              >
            <fieldset>
                <ol>
                    <?php echo $form->render(); ?>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                <p>
                    <input type="button" class="" id="btnSave" value="<?php echo __("Save"); ?>"  />
                </p>
            </fieldset>
        </form>
    </div>



</div> <!-- Box -->