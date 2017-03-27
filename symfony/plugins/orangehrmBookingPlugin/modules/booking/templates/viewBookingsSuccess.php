<?php
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/fullcalendar.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/scheduler.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.datetimepicker.full.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewBookings.js'));
?>

<script type="text/javascript">    
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
    var bookingFormUrl = '<?= url_for('@form_booking') ?>';
    var saveBookingUrl = '<?= url_for('@save_booking'); ?>';
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

<?php include_partial('bookingModals', array()) ?>