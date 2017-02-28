<?php
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/addBooking.js'));

$partialParams = array(
  'form' => $form,
  'actionForm' => url_for('@add_booking'),
  'buttons' => array(
    array('id' => 'btnSave', 'value' => __("Save")),
  )
);
?>

<div class="box">
    <div class="head">
        <h1><?php echo __('Add a Booking'); ?></h1>
    </div>

    <div class="inner" id="addBookingTbl">
        <?php
        include_partial('global/flash_messages');
        include_partial('bookingForm', $partialParams);
        ?>

    </div>
</div>

<script type="text/javascript">
  var customerProjectUrl = '<?= url_for('@customer_projects'); ?>';
  var bookableWorkShiftsUrl = '<?= url_for('@bookable_workshifts'); ?>';
  var BOOKING_HOURS = '<?= Booking::BOOKING_TYPE_HOURS ?>';
  var BOOKING_SPECIFIC_TIME = '<?= Booking::BOOKING_TYPE_SPECIFIC_TIME ?>';
  var firstDayOfWeek = <?= $firstDayOfWeek; ?>;
</script>
