<?php use_stylesheet(plugin_web_path('orangehrmBookingPlugin', 'css/spectrum.css')); ?>
<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/spectrum.js')); ?>

<div class="box bookableForm" id="bookable-information">
    <div class="head">
        <h1><?php echo __("Bookable Resource Details") ?></h1>
    </div>
    <div class="inner">

        <form id="bookableform" name="frmBookable" method="post" action="<?php echo url_for('@view_bookable'); ?>">

            <fieldset>

                <ol>
                    <li>
                        <label><?= __("Employee") ?></label>
                        <p><?= $bookableName ?></p>
                    </li>
                    <?php echo $form->render(); ?>
                </ol>

                <p>
                    <input type="button" class="" id="btnSave" value="<?php echo __("Edit"); ?>"  />
                </p>
            </fieldset>

        </form>

    </div>

</div>

<script type="text/javascript">
  var edit = "<?php echo __("Edit"); ?>";
  var save = "<?php echo __("Save"); ?>";
</script>

<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewBookableResource.js')); ?>