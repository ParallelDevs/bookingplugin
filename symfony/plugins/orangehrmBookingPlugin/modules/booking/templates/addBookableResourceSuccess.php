<?php
use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/orangeBookingPlugin.min.css'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/addBookableResource.min.js'));

$partialParams = array(
  'form' => $form,
  'actionForm' => url_for('@add_bookable'),
  'buttonValue' => __("Save"),
);
?>
<div class="box">
    <div class="head">
        <h1><?php echo __('Set Employee as Bookable Resource'); ?></h1>
    </div>

    <div class="inner" id="addBookableTbl">
        <?php
        include_partial('global/flash_messages');
        include_partial('bookableResourceForm', $partialParams);
        ?>
    </div>

</div> <!-- Box -->

