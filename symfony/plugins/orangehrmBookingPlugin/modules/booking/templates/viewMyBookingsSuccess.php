<?php
if (!empty($bookableId)) {
  use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/jquery.datetimepicker.min.css'));
  use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/fullcalendar.min.css'));
  use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/scheduler.min.css'));
  use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
  use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js'));
  use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/fullcalendar.min.js'));
  use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/scheduler.min.js'));
  use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.datetimepicker.full.min.js'));
  use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewMyBookings.min.js'));
}
?>

<?php if (!empty($bookableId)): ?>
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
<?php endif; ?>

<div class="box" id="bookings">
  <div class="head">
    <h1><?php echo __("My Schedule") ?></h1>
  </div>
  <div class="inner">
    <?php if (!empty($bookableId)): ?>
      <div class="filter-form">
        <label for="searchStartDate"><?= __("From") ?></label>
        <input type="text" id="searchStartDate" name="searchStartDate"/>
        <label for="searchEndDate"><?= __("To") ?></label>
        <input type="text" id="searchEndDate" name="searchEndDate"/>
        <button id="" class="btn filter"><?= __("Filter") ?></button>
        <button id="" class="btn clear"><?= __("Reset") ?></button>
      </div>

      <div id='calendar'></div>

    <?php else : ?>
      <p class="message">
        <?php echo __("Your account has not been configured for having bookings."); ?>
      </p>
    <?php endif; ?>
  </div>
</div>

