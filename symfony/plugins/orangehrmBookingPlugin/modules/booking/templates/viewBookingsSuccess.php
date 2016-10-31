<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/fullcalendar.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/scheduler.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/bookings.css')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/fullcalendar.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/scheduler.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/bookings.js')); ?>

<script type="text/javascript">
  $(function () { // document ready

      $('#calendar').fullCalendar({
    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    now: '<?= date('Y-m-d') ?>',
    selectable: true,
    selectHelper: true,
    editable: true,
    aspectRatio: 4.0,
    firstDay: 1,
    header: {
        left: 'today prev,next',
        center: 'title',
        right: 'timelineDay,timelineWeek,timelineMonth'
    },
    defaultView: 'timelineMonth',
    resourceAreaWidth: '25%',
    resourceLabelText: '<?= __("Resources") ?>',
    resources: {
        url: '<?= url_for('@bookables_json') ?>',
        type: 'POST',
        error: errorResourceHandler,
    },
    events: {
        url: '<?= url_for('@bookings_json') ?>',
        data: {
      mode: 'timeline',
        },
        type: 'POST',
        error: errorEventHandler,
    },
    select: selectHandler,
    unselect: unselectHandler,
    eventClick: eventClickHandler,
    eventMouseover: eventMouseoverHandler,
    eventMouseout: eventMouseoutHandler,
    eventRender: renderEventHandler,
    selectAllow: selectAllowHandler,
    selectOverlap: selectOverlapHandler,
    eventOverlap: eventOverlapHandler,
    eventResize: eventResizeHandler,
      });

  });

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