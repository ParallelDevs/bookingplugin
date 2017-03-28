//=include _booking.form.js

var activeModalId = '';

function initModalFields() {
    initDateField('#startDate');
    initDateField('#endDate');
}

function ajaxLoadNewBooking() {
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

function ajaxLoadEditBooking(revertFunction) {
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

function ajaxSaveBooking() {
    $.ajax({
        type: "POST",
        url: saveBookingUrl,
        data: $('.form-booking-plugin').serialize(),
        cache: false,
        success: successBookingForm,
        dataType: "json",
    });
}

function successBookingForm(data) {
    if (data.success) {
        $(activeModalId).modal('hide');
    } else {
        var length = data.errors.length;
        for (var i = 0; i < length; i++) {
            console.log(data.errors[i]);
            // Show error
        }
    }
}

function refreshBookings() {
    $('#calendar').fullCalendar('refetchEvents');
    holidayOverlap = false;
}

jQuery(document).ready(function () {
    $("#addBooking, #editBooking").on("hide.bs.modal", function () {
        $(this).find('.modal-body').empty();
        refreshBookings();
    });

    $("#addBooking, #editBooking").on('change', '#customerId', customerChangeHandler);

    $("#addBooking, #editBooking").on('change', '#startDate', function () {
        startDateChangeHandler("#startDate", "#endDate");
    });

    $("#addBooking").on('click', ".btn.save", function () {
        activeModalId = "#addBooking";
        ajaxSaveBooking();
    });

    $("#editBooking").on('click', ".btn.save", function () {
        activeModalId = "#editBooking";
        ajaxSaveBooking();
    });
});
