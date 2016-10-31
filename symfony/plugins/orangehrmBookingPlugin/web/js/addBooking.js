var minTime = '00:00';
var maxTime = '23:59';

function fillProjectSelect(id, data) {
    var $select = $(id);
    $select.find('option').remove();
    $.each(data, function (key, value) {
	$('<option>').val(value.projectId).text(value.name).appendTo($select);
    });
}

function setBookableWorkShift(data) {
    minTime = data.minTime;
    maxTime = data.maxTime;
    /*$('#startTime').timepicker('option', 'minTime', data.minTime);
     $('#startTime').timepicker('option', 'maxTime', data.maxTime);
     $('#endTime').timepicker('option', 'minTime', data.minTime);
     $('#endTime').timepicker('option', 'maxTime', data.maxTime);*/

   /* jQuery('#startAt').datetimepicker({
	'minTime': minTime,
	'maxTime': maxTime,
    });*/
}

$(document).ready(function () {
    /*daymarker.bindElement("#startDate", function () {});
     
     daymarker.bindElement("#endDate", function () {});
     
     $('#startTime').timepicker({
     showPeriod: true,
     showLeadingZero: true
     });
     
     $('#endTime').timepicker({
     showPeriod: true,
     showLeadingZero: true
     });*/

    $("#btnSave").click(function () {
	$("#addBookingForm").submit();
    });

    /*$('#allDay').change(function () {
     if ($(this).is(':checked')) {
     $('#startTime').parent().hide();
     $('#endTime').parent().hide();
     $('#startTime').val(minTime).change();
     $('#endTime').val(maxTime).change();
     } else {
     $('#startTime').parent().show();
     $('#endTime').parent().show();
     }
     });*/
});
