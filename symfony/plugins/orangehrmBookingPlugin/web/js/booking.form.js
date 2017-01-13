function fillProjectSelect(id, data) {
    var $select = $(id);
    $select.find('option').remove();
    $.each(data, function (key, value) {
        $('<option>').val(value.projectId).text(value.name).appendTo($select);
    });
}

function setBookableWorkShift(data) {

    var momentMinTime = moment(data.minTime, 'H:m');
    var momentMaxTime = moment(data.maxTime, 'H:m');
    momentMaxTime.add(30, 'minutes');

    jQuery('#startTime').datetimepicker({
        minTime: momentMinTime.toDate(),
        maxTime: momentMaxTime.toDate()
    });
    jQuery('#startTime').datetimepicker({
        minTime: momentMinTime.toDate(),
        maxTime: momentMaxTime.toDate()
    });

}


jQuery(document).ready(function () {
    $(".specific-time").hide();

    $("#customerId").change(function () {
        var id = $(this).val();
        if (id != '') {
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
        formatDate: 'Y-m-d'
    });

    jQuery('#endDate').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d'
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