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
  var inactiveResourceTooltip = '<?= __("Resource is inactive") ?>';
  var holidayLabel = '<?= __("Holiday:") ?>';
  var bookableResourceTitle = '<?= __("Resources") ?>';
  var filterBtnLabel = '<?= __("Filter") ?>';
  var confirmBookingNonBusiness = '<?= __("Are you sure you want a booking in a non business day?") ?>';
  var confirmStartBookingNonBusiness = '<?= __("Are you sure you want to start a booking in a non business day?") ?>';
  var confirmEndBookingNonBusiness = '<?= __("Are you sure you want to end a booking in a non business day?") ?>';
  var confirmBookingHoliday = '<?= __("Are you sure you want a booking during a holiday?") ?>';
  var confirmDeleteBooking = '<?= __("Are you sure you want to delete this booking? This action cannot be undone.") ?>';
  var bookableResourcesUrl = '<?= url_for("@bookables_json") ?>';
  var bookingResourcesUrl = '<?= url_for("@bookings_json") ?>';
  var customerProjectUrl = '<?= url_for("@customer_projects"); ?>';
  var bookableWorkShiftsUrl = '<?= url_for("@bookable_workshifts"); ?>';
  var bookingFormUrl = '<?= url_for("@form_booking") ?>';
  var saveBookingUrl = '<?= url_for("@save_booking"); ?>';
  var deleteBookingUrl = '<?= url_for("@delete_booking"); ?>';
</script>

<div class="box" id="bookings">
    <div class="head">
        <h1><?php echo __("Bookings") ?></h1>
    </div>
    <div class="inner">        
        <input type="text" id="searchStartDate" name="searchStartDate"/>
        <input type="text" id="searchEndDate" name="searchEndDate"/>
        <div id='calendar'></div>
    </div>
</div>

<?php include_partial('bookingModals', array()) ?>