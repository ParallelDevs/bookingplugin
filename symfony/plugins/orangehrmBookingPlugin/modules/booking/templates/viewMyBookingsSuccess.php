<?php
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/fullcalendar.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/scheduler.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.datetimepicker.full.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewMyBookings.js'));
?>

<script type="text/javascript">
  var calendarMinTime = '<?= $minTime ?>';
  var calendarMaxTime = '<?= $maxTime ?>';
  var firstDayOfWeek = <?= $firstDayOfWeek; ?>;
  var inactiveResourceTooltip = '<?= __('Resource is inactive') ?>';
  var holidayLabel = '<?= __('Holiday:') ?>';
  var bookableId = '<?= $bookableId ?>';
  var bookableResourcesUrl = '<?= url_for('@bookables_json') ?>';
  var bookingResourcesUrl = '<?= url_for('@bookings_json') ?>';
</script>

<div class="box" id="bookings">
    <div class="head">
        <h1><?php echo __("My Bookings") ?></h1>
    </div>
    <div class="inner">
        <div class="filter-form">
            <label for="searchStartDate"><?= __("From") ?></label>
            <input type="text" id="searchStartDate" name="searchStartDate"/>
            <label for="searchEndDate"><?= __("To") ?></label>
            <input type="text" id="searchEndDate" name="searchEndDate"/>
            <button id="" class="btn filter"><?= __("Filter") ?></button>
        </div>

        <div id='calendar'></div>
    </div>
</div>

