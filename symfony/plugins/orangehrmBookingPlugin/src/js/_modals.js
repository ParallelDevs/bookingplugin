//=include _booking.form.js

function initModalFields() {
    initDateField('#startDate');
    initDateField('#endDate');
}

jQuery(document).ready(function () {
    $("#addBooking, #editBooking").on("hide.bs.modal", function () {
        $(this).find('.modal-body').empty();
    });

    $("#addBooking, #editBooking").on('change', '#customerId', customerChangeHandler);

    $("#addBooking, #editBooking").on('change', '#startDate', function () {
        startDateChangeHandler("#startDate", "#endDate");
    });
    
    $(".booking .btn.reset").click(function(){
        $('#calendar').fullCalendar('refetchEvents');
    });
});