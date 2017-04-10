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
            $('#addBooking').find('.modal-body')
                    .html(response);
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
            $('#editBooking').find('.modal-body')
                    .html(response);
            initModalFields();
            $('#editBooking').modal('show');
        },
        fail: function () {
            revertCalendar(revertFunction);
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

function ajaxDeleteBooking(){
    bookingId = $("#bookingId").val();
    $.ajax({
        type: "POST",
        url: deleteBookingUrl,
        data: {
            "bookingId": bookingId
        },
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
            var $field = $('#' + data.errors[i].field);
            $('<span>').addClass('validation-error')
                    .addClass(data.errors[i].field)
                    .attr('generated', 'true')
                    .text(data.errors[i].message)
                    .insertAfter($field);
            $field.addClass('error-field');
        }
    }
}

function refreshBookings() {
    $('#calendar').fullCalendar('refetchEvents');
    holidayEvent = null;
}

jQuery(document).ready(function () {
    $("#addBooking, #editBooking").on("hide.bs.modal", function () {
        $(this).find('.modal-body')
                .empty();
        refreshBookings();
    });

    $("#addBooking, #editBooking").on('change', '#customerId', customerChangeHandler);

    $("#addBooking, #editBooking").on('change', '#startDate', function () {
        startDateChangeHandler("#startDate", "#endDate");
    });

    $("#addBooking").on('click', ".btn.save", function () {
        activeModalId = "#addBooking";
        $("#addBooking .form-booking-plugin").find(".validation-error")
                .remove();
        $("#addBooking .form-booking-plugin").find(".error-field")
                .removeClass('error-field');
        $("#addBooking .form-booking-plugin input").removeClass('error-field');
        ajaxSaveBooking();
    });

    $("#editBooking").on('click', ".btn.save", function () {
        activeModalId = "#editBooking";
        $("#editBooking .form-booking-plugin").find(".validation-error")
                .remove();
        $("#editBooking .form-booking-plugin").find(".error-field")
                .removeClass('error-field');
        $("#editBooking .form-booking-plugin input").removeClass('error-field');
        ajaxSaveBooking();
    });

    $("#editBooking").on('click', ".btn.delete", function () {
        activeModalId = "#editBooking";
        if (confirm(confirmDeleteBooking)) {
            ajaxDeleteBooking();
        }
    });
});
