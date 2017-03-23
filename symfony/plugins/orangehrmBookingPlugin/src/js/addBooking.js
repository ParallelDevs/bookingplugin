//=include _booking.form.js

function lockForm() {
    $("#startDate").attr('disabled', 'disabled');
    $("#endDate").attr('disabled', 'disabled');
    $("#startTime").attr('disabled', 'disabled');
    $("#endTime").attr('disabled', 'disabled');
    $('#hours').attr('disabled', 'disabled');
    $('#minutes').attr('disabled', 'disabled');
    $('#customerId').attr('disabled', 'disabled');
    $('#projectId').attr('disabled', 'disabled');
}

function unlockForm() {
    $("#startDate").removeAttr('disabled');
    $("#endDate").removeAttr('disabled');
    $("#startTime").removeAttr('disabled');
    $("#endTime").removeAttr('disabled');
    $('#hours').removeAttr('disabled');
    $('#minutes').removeAttr('disabled');
    $('#customerId').removeAttr('disabled');
    $('#projectId').removeAttr('disabled');
}

function setBookableWorkShift(data) {
    var momentMinTime = moment(data.minTime, 'H:m');
    var momentMaxTime = moment(data.maxTime, 'H:m');

    jQuery('#minStartTime').val(momentMinTime.format('HH:mm:ss'));
    jQuery('#maxEndTime').val(momentMaxTime.format('HH:mm:ss'));

    momentMaxTime.add(15, 'minutes');

    jQuery('#startTime').datetimepicker({
        minTime: momentMinTime.toDate(),
        maxTime: momentMaxTime.toDate()
    });

    jQuery('#endTime').datetimepicker({
        minTime: momentMinTime.toDate(),
        maxTime: momentMaxTime.toDate()
    });

}

jQuery(document).ready(function () {
    $("#bookableId").change(function () {
        var id = $(this).val();
        if (id !== '') {
            $.ajax({
                type: "POST",
                url: bookableWorkShiftsUrl,
                data: {bookableId: id},
                cache: false,
                success: function (data)
                {
                    unlockForm();
                    setBookableWorkShift(data);
                }
            });
        }
    });

    $("#customerId").change(customerChangeHandler);

    $("#startDate").change(function () {
        startDateChangeHandler("#startDate", "#endDate");
    });
    
    initDateField('#startDate');
    initDateField('#endDate');

    $("#btnSave").click(function () {
        $("#frmBooking").submit();
    });

    if ($("#bookableId").val() !== '') {
        $("#bookableId").change();
    } else {
        lockForm();
    }

    if ($("#customerId").val() !== '') {
        $("#customerId").change();
    }
});