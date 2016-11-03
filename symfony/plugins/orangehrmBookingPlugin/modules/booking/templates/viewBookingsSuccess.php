<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/fullcalendar.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/scheduler.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/jquery.datetimepicker.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/bookings.css')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/fullcalendar.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/scheduler.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.datetimepicker.full.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/booking.common.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewBookings.js')); ?>

<script type="text/javascript">
  var addBookingTitle = '<?= __('Add Booking') ?>';
  var editBookingTitle = '<?= __('Edit Booking') ?>';
  var currentDate = '<?= date('Y-m-d') ?>';
  var calendarMinTime = '<?= $minTime ?>';
  var calendarMaxTime = '<?= $maxTime ?>';
  var bookableResourceTitle = '<?= __("Resources") ?>';
  var bookableResourcesUrl = '<?= url_for('@bookables_json') ?>';
  var bookingResourcesUrl = '<?= url_for('@bookings_json') ?>';

</script>

<div class="box" id="bookings">
    <div class="head">
        <h1><?php echo __("Bookings") ?></h1>
    </div>
    <div class="inner">
        <div id='loading'></div>

        <div id='calendar'></div>
    </div>
</div>

<?php include_partial('modalsBooking', array('form' => $bookingForm)) ?>