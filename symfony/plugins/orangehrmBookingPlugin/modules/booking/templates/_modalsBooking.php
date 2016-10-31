<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/jquery.timepicker.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/addBooking.css')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.timepicker.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/addBooking.js')); ?>

<div class="modal hide" id="bookingDialog">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Add Booking') ?></h3>
    </div>
    <div class="modal-body">
        <form id="addBookingForm" method="post" action="">
            <fieldset>
                <ol>
                    <?php echo $form->render() ?>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>

                </ol>
            </fieldset>
        </form>
    </div>
    <div class="modal-footer">
        <input type="button"  id="dialogSave" name="dialogSave" class="btn" value="<?php echo __('Save'); ?>" />
        <input type="button"  id="dialogCancel" name="dialogCancel" class="btn reset" data-dismiss="modal"
               value="<?php echo __('Cancel'); ?>" />
    </div>
</div>

<div class="modal hide" id="newBookingAllDayCollisionDialog">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Collision Detected') ?></h3>
    </div>
    <div class="modal-body">
        <p class="message">
            <?= __('New booking collides with ') ?>
            <span id="bookingCollisionName"></span>
            <?= __('. The latter is an all day booking, so there is no free time for allocating the new booking.') ?>
        </p>
    </div>
    <div class="modal-footer">
    </div>
</div>

<div class="modal hide" id="bookingAllDayCollisionDialog">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Collision Detected') ?></h3>
    </div>
    <div class="modal-body">
        <p class="message">
            <span id="bookingColl1"></span>
            <?= __(' collides with ') ?>
            <span id="bookingColl2"></span>
            <?= __(', and at least one of them is an all day booking, so there is no free time for allocating the booking.') ?>
        </p>
    </div>
    <div class="modal-footer">
    </div>
</div>

<div class="modal hide" id="bookingTimeCollisionDialog">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Possible Collision') ?></h3>
    </div>
    <div class="modal-body">
        <p class="message">
            <span id="bookingTimeColl1"></span>
            <?= __(' may collide with ') ?>
            <span id="bookingTimeColl2"></span>
            <?= __('. Their start/end hours are similar.') ?></p>
    </div>
    <div class="modal-footer">
    </div>
</div>

<script type="text/javascript">
  jQuery(document).ready(function () {
      $("#customerId").change(function () {
    var id = $(this).val();
    if (id != '') {
        $.ajax({
      type: "POST",
      url: '<?= url_for('@customer_projects'); ?>',
      data: {customerId: escape(id)},
      cache: false,
      success: function (data)
      {
          fillProjectSelect('#projectId', data);
      }
        });
    }
      });

      $("#dialogSave").click(function () {
    $.ajax({
        type: "POST",
        url: '<?= url_for('@add_booking_ajax'); ?>',
        data: $('#addBookingForm').serialize(),
        cache: false,
        success: successAddBooking,
        dataType: "json"
    });
      });

      $("#dialogCancel").click(function () {
    cleanAddBookingForm();
      });
  });

</script>