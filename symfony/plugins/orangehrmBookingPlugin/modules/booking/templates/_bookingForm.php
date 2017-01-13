<form id="frmBooking" name="frmBooking" class="form-booking-plugin" method="post" action="<?= $actionForm ?>" >
    <fieldset>
        <ol>
            <li>
                <?= $form['bookingId']->render() ?>
                <?= $form['duration']->render() ?>
                <?= $form['bookingType']->render() ?>
            </li>
            <li>
                <?= $form['bookableId']->renderLabel() ?>
                <?= $form['bookableId']->render() ?>
            </li>
            <li class="input-group-inline">
                <?= $form['startDate']->renderLabel() ?>
                <?= $form['startDate']->render() ?>
                <?= $form['endDate']->renderLabel() ?>
                <?= $form['endDate']->render() ?>
            </li>
            <li class="duration">
                <?= $form['duration']->renderLabel() ?>
                <?= $form['hours']->render() ?>
                <span title="<?= __('Hours')?>">H</span>
                <?= $form['minutes']->render() ?>
                <span title="<?= __('Minutes')?>">M</span>
                <a id="btn-booking-time" class="">
                    <?= __('Book specific time') ?>
                </a>
            </li>
            <li class="specific-time input-group-inline">
                <?= $form['startTime']->renderLabel() ?>
                <?= $form['startTime']->render() ?>
                <?= $form['endTime']->renderLabel() ?>
                <?= $form['endTime']->render() ?>
                <a id="btn-booking-duration" class="">
                    <?= __('Book hours') ?>
                </a>
            </li>
            <li>
                <?= $form['customerId']->renderLabel() ?>
                <?= $form['customerId']->render() ?>
            </li>
            <li>
                <?= $form['projectId']->renderLabel() ?>
                <?= $form['projectId']->render() ?>
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
