<form id="frmBookable" method="post" action="<?= $actionForm ?>" >
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