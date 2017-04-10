<form id="frmBooking" name="frmBooking" class="form-booking-plugin" method="post" action="<?= $actionForm ?>" >
    <fieldset>
        <ol>
            <?php if ($form->hasGlobalErrors()): ?>
              <li>
                  <?= $form->renderGlobalErrors() ?>
              </li>
            <?php endif; ?>
            <li class="hidden-fields">
                <?= $form->renderHiddenFields() ?>
            </li>
            <li class="booking-bookable">
                <?= $form['bookableId']->renderLabel() ?>
                <?= $form['bookableId']->render() ?>
                <?= $form['bookableName']->render() ?>
            </li>
            <li class="booking-date">
                <?= $form['startDate']->renderLabel() ?>
                <?= $form['startDate']->render() ?>
                <?= $form['endDate']->renderLabel() ?>
                <?= $form['endDate']->render() ?>
            </li>
            <li class="booking-duration">
                <?= $form['duration']->renderLabel() ?>
                <?= $form['hours']->render() ?>
                <span title="<?= __('Hours') ?>" class="time-label">H</span>
                <?= $form['minutes']->render() ?>
                <span title="<?= __('Minutes') ?>" class="time-label">M</span>
            </li>
            <li class="booking-customer">
                <?= $form['customerId']->renderLabel() ?>
                <?= $form['customerId']->render() ?>                
            </li>
            <li class="booking-project">
                <?= $form['projectId']->renderLabel() ?>
                <?= $form['projectId']->render() ?>                
            </li>            
        </ol>
        <p>
            <?php foreach ($buttons as $button) : ?>
              <input type="button" class="" id="<?= $button['id'] ?>" value="<?= $button['value'] ?>"  />
            <?php endforeach; ?>
        </p>
    </fieldset>
</form>
