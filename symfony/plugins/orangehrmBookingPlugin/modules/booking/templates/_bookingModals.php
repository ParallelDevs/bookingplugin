<div id="addBooking" class="booking modal hide">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3 class="booking-dialog-title"><?= __('Add Booking') ?></h3>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <input type="button" name="dialogSave" class="btn save" value="<?= __('Save'); ?>" />
        <input type="button" name="dialogCancel" class="btn reset" data-dismiss="modal"
               value="<?= __('Cancel'); ?>" />
    </div>
</div>

<div id="editBooking" class="booking modal hide">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3 class="booking-dialog-title"><?= __('Edit Booking') ?></h3>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <input type="button" name="dialogSave" class="btn save" value="<?= __('Save'); ?>" />
        <input type="button" name="dialogDelete" class="btn delete" value="<?= __('Delete'); ?>" />
        <input type="button" name="dialogCancel" class="btn reset" data-dismiss="modal"
               value="<?= __('Cancel'); ?>" />
    </div>
</div>
