<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/fullcalendar.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/scheduler.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/booking.common.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/viewMyBookings.css')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/fullcalendar.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/scheduler.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/booking.common.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewMyBookings.js')); ?>


<script type="text/javascript">
  var currentDate = '<?= date('Y-m-d') ?>';
  var calendarMinTime = '<?= $minTime ?>';
  var calendarMaxTime = '<?= $maxTime ?>';
  var bookableResourcesUrl = '<?= url_for('@bookables_json') ?>';
  var bookingResourcesUrl = '<?= url_for('@bookings_json') ?>';
  var inactiveResourceTooltip = '<?= __('Resource is inactive') ?>';
  var holidayLabel = '<?= __('Holiday:') ?>';
  var bookableId = '<?= $bookableId ?>';
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

