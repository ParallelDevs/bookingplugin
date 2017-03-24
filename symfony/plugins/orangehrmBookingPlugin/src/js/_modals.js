//=include _booking.form.js

function initModalFields() {
    initDateField('#startDate');
    initDateField('#endDate');
}

function ajaxAddBooking() {
    $.ajax({
        type: 'POST',
        url: bookingFormUrl,
        data: {
            "bookableId": bookableId,
            "bookableName": bookableName,
            "startDate": startDate,
            "endDate": endDate,
            "minStartTime": minStartTime,
            "maxEndTime": maxEndTime
        },
        success: function (response) {
            $('#addBooking').find('.modal-body').html(response);
            initModalFields();
            $('#addBooking').modal('show');
        }
    });
}

function ajaxEditBooking(revertFunction){
    $.ajax({
        type: 'POST',
        url: bookingFormUrl,
        data: {
            "bookingId": bookingId,
            "bookableId": bookableId,            
            "startDate": startDate,
            "endDate": endDate,
            "minStartTime": minStartTime,
            "maxEndTime": maxEndTime
        },
        success: function (response) {
            $('#editBooking').find('.modal-body').html(response);
            initModalFields();
            $('#editBooking').modal('show');
        },
        fail: function () {
            revertFunction();
        }
    });
}


jQuery(document).ready(function () {
    $("#addBooking, #editBooking").on("hide.bs.modal", function () {
        $(this).find('.modal-body').empty();
        $('#calendar').fullCalendar('refetchEvents');
    });

    $("#addBooking, #editBooking").on('change', '#customerId', customerChangeHandler);

    $("#addBooking, #editBooking").on('change', '#startDate', function () {
        startDateChangeHandler("#startDate", "#endDate");
    });
});