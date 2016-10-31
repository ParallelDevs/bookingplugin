<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/jquery.datetimepicker.min.css')); ?>
<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/addBooking.css')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.datetimepicker.full.min.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/addBooking.js')); ?>

<div class="box">
    <div class="head">
        <h1><?php echo __('Add a Booking'); ?></h1>
    </div>

    <div class="inner" id="addBookingTbl">
        <?php include_partial('global/flash_messages'); ?>
        <form id="addBookingForm" method="post" action="<?php echo url_for('@add_booking'); ?>">
            <fieldset>
                <ol>
                    <?php echo $form->render() ?>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>

                </ol>
                <p>
                    <input type="button" class="" id="btnSave" value="<?php echo __("Save"); ?>"  />
                </p>
            </fieldset>
        </form>
    </div>
</div>

<script type="text/javascript">
  jQuery(document).ready(function () {
      $("#customerId").change(function () {
	  var id = $(this).val();
	  if (id != '') {
	      $.ajax({
		  type: "GET",
		  url: '<?php echo url_for('@customer_projects'); ?>' + '?customerId=' + id,
		  cache: false,
		  success: function (data)
		  {
		      fillProjectSelect('#projectId', data);
		  }
	      });
	  }
      });

      $("#bookableId").change(function () {
	  var id = $(this).val();
	  if (id != '') {
	      $.ajax({
		  type: "GET",
		  url: '<?php echo url_for('@bookable_workshifts'); ?>' + '?bookableId=' + id,
		  cache: false,
		  success: function (data)
		  {
		      setBookableWorkShift(data);
		  }
	      });
	  }
      });


      if ($("#bookableId").val() !== '') {
	  $("#bookableId").change();
      }

      if ($("#customerId").val() !== '') {
	  $("#customerId").change();
      }

      $('#startAt').datetimepicker({
	  format: '<?= sfContext::getInstance()->getUser()->getDateFormat() ?> H:i',
	  step: 30,
	  minTime: '02:00',
      });
      $('#endAt').datetimepicker({
	  format: '<?= sfContext::getInstance()->getUser()->getDateFormat() ?> H:i',
	  step: 30,
      });
  });

</script>
