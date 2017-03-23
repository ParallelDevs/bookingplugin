//=include _booking.form.js

function initModalFields() {
    initDateField('#startDate');
    initDateField('#endDate');
}

jQuery(document).ready(function () {
    $('#addBooking').on('change', '#customerId', customerChangeHandler);
    $('#addBooking').on('change', '#startDate', function () {
        startDateChangeHandler("#startDate", "#endDate");
    });
});