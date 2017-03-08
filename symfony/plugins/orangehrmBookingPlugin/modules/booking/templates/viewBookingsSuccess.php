<?php
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/fullcalendar.min.css'));
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/scheduler.min.css'));
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/jquery.datetimepicker.min.css'));
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/fullcalendar.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/scheduler.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.datetimepicker.full.min.js'));
?>

<script type="text/javascript">
  //var addBookingTitle = '<?= __('Add Booking') ?>';
  //var editBookingTitle = '<?= __('Edit Booking') ?>';
    var currentDate = '<?= date('Y-m-d') ?>';
    var calendarMinTime = '<?= $minTime ?>';
    var calendarMaxTime = '<?= $maxTime ?>';
    var firstDayOfWeek = <?= $firstDayOfWeek; ?>;
    var inactiveResourceTooltip = '<?= __('Resource is inactive') ?>';
    var holidayLabel = '<?= __('Holiday:') ?>';
    var bookableResourceTitle = '<?= __("Resources") ?>';
    var bookableResourcesUrl = '<?= url_for('@bookables_json') ?>';
    var bookingResourcesUrl = '<?= url_for('@bookings_json') ?>';
    var customerProjectUrl = '<?= url_for('@customer_projects'); ?>';
    var bookableWorkShiftsUrl = '<?= url_for('@bookable_workshifts'); ?>';
    var BOOKING_HOURS = '<?= Booking::BOOKING_TYPE_HOURS ?>';
    var BOOKING_SPECIFIC_TIME = '<?= Booking::BOOKING_TYPE_SPECIFIC_TIME ?>';
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

