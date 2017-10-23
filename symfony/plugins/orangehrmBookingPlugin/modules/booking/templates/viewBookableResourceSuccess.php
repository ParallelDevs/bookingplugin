<?php
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewBookableResource.js'));

$partialParams = array(
  'form' => $form,
  'actionForm' => url_for('@view_bookable'),
  'buttonValue' => __("Edit"),
);
?>

<div class="box bookableForm" id="bookable-information">
    <div class="head">
        <h1><?php echo __("Bookable Resource Details") ?></h1>
    </div>
    <div class="inner">
<?php
include_partial('global/flash_messages');
include_partial('bookableResourceForm', $partialParams);
?>             

    </div>

</div>

<script type="text/javascript">
  var edit = "<?php echo __("Edit"); ?>";
  var save = "<?php echo __("Save"); ?>";
</script>
