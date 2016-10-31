<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/spectrum.css')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/spectrum.js')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/addBookableResource.js')); ?>

<div class="box">
    <div class="head">
        <h1><?php echo __('Set Employee as Bookable Resource'); ?></h1>
    </div>

    <div class="inner" id="addBookableTbl">
        <?php include_partial('global/flash_messages'); ?>
        <form id="frmAddBookable" method="post" action="<?php echo url_for('@add_bookable'); ?>"
              >
            <fieldset>
                <ol>
                    <?php echo $form->render(); ?>
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

</div> <!-- Box -->

