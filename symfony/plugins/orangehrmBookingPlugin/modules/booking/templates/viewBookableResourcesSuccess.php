<div class="box searchForm toggableForm" id="employee-information">
    <div class="head">
        <h1><?php echo __("Bookable Resources") ?></h1>
    </div>
    <div class="inner">
        <form id="search_form" name="frmBookableSearch" method="post" action="<?php echo url_for('@bookable_list'); ?>">

            <fieldset>

                <ol>
                    <?php echo $form->render(); ?>
                </ol>

                <input type="hidden" name="pageNo" id="pageNo" value="" />
                <input type="hidden" name="hdnAction" id="hdnAction" value="search" />

                <p>
                    <input type="button" id="searchBtn" value="<?php echo __("Search") ?>" name="_search" />
                    <input type="button" class="reset" id="resetBtn" value="<?php echo __("Reset") ?>" name="_reset" />
                </p>

            </fieldset>

        </form>

    </div>

    <a href="#" class="toggle tiptip" title="<?php echo __(CommonMessages::TOGGABLE_DEFAULT_MESSAGE); ?>">&gt;</a>


</div>

<?php include_component('core', 'ohrmList'); ?>

<?php use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/viewBookableResources.js')); ?>