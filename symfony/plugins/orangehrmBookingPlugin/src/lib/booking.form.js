function fillProjectSelect(id, data) {
    var $select = $(id);
    $select.find('option').remove();
    $.each(data, function (key, value) {
        $('<option>').val(value.projectId).text(value.name).appendTo($select);
    });
}

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
    momentMaxTime.add(15, 'minutes');
    
    jQuery('#minStartTime').val(momentMinTime.format('HH:mm:ss'));
    
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
    $(".specific-time").hide();

    var id = $("#bookableId").val();
    if (id === '') {
        lockForm();
    }

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

    $("#customerId").change(function () {
        var id = $(this).val();
        if (id !== '') {
            $.ajax({
                type: "POST",
                url: customerProjectUrl,
                data: {customerId: id},
                cache: false,
                success: function (data)
                {
                    fillProjectSelect('#projectId', data);
                }
            });
        }
    });

    jQuery('#startDate').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        dayOfWeekStart: firstDayOfWeek
    });

    jQuery('#endDate').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        dayOfWeekStart: firstDayOfWeek
    });

    jQuery('#startTime').datetimepicker({
        datepicker: false,
        timepicker: true,
        format: 'H:i',
        formatTime: 'H:i',
	step: 15,
        dayOfWeekStart: firstDayOfWeek
    });

    jQuery('#endTime').datetimepicker({
        datepicker: false,
        timepicker: true,
        format: 'H:i',
        formatTime: 'H:i',
	step: 15,
        dayOfWeekStart: firstDayOfWeek
    });

    $("#btn-booking-time").click(function () {
        $(".duration").fadeOut(800, function () {
            $(".specific-time").fadeIn(400);
        });
        $("#bookingType").val(BOOKING_SPECIFIC_TIME);
    });

    $("#btn-booking-duration").click(function () {
        $(".specific-time").fadeOut(800, function () {
            $(".duration").fadeIn(400);
        });
        $("#bookingType").val(BOOKING_HOURS);
    });
});