<form id="frmBooking" name="frmBooking" class="form-booking-plugin" method="post" action="<?= $actionForm ?>" >
    <fieldset>
        <ol>
            <?php if ($form->hasGlobalErrors()): ?>
                <li>
                    <?= $form->renderGlobalErrors() ?>
                </li>
            <?php endif; ?>
            <li>
                <?= $form->renderHiddenFields() ?>
            </li>
            <li>
                <?= $form['bookableId']->renderLabel() ?>
                <?= $form['bookableId']->render() ?>
                <?= $form['bookableId']->renderError() ?>
            </li>
            <li class="event-date input-group-inline">
                <?= $form['startDate']->renderLabel() ?>
                <?= $form['startDate']->render() ?>
                <?= $form['startDate']->renderError() ?>
                <?= $form['endDate']->renderLabel() ?>
                <?= $form['endDate']->render() ?>
                <?= $form['endDate']->renderError() ?>
            </li>
            <li class="booking-duration">
                <?= $form['duration']->renderLabel() ?>
                <?= $form['hours']->render() ?>
                <?= $form['hours']->renderError() ?>
                <span title="<?= __('Hours') ?>">H</span>
                <?= $form['minutes']->render() ?>
                <?= $form['minutes']->renderError() ?>                
                <span title="<?= __('Minutes') ?>">M</span>                
            </li>            
            <li>
                <?= $form['customerId']->renderLabel() ?>
                <?= $form['customerId']->render() ?>
                <?= $form['customerId']->renderError() ?>
            </li>
            <li>
                <?= $form['projectId']->renderLabel() ?>
                <?= $form['projectId']->render() ?>
                <?= $form['projectId']->renderError() ?>
            </li>
            <li class="required">
                <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
            </li>
        </ol>
        <p>
            <?php foreach ($buttons as $button) : ?>
                <input type="button" class="" id="<?= $button['id'] ?>" value="<?= $button['value'] ?>"  />
            <?php endforeach; ?>
        </p>
    </fieldset>
</form>
