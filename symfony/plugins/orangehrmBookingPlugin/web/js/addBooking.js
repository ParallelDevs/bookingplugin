function setBookableWorkShift(data) {
    bookableMinTime = data.minTime;
    bookableMaxTime = data.maxTime;

    var momentMinTime = moment(bookableMinTime, 'H:m');
    var momentMaxTime = moment(bookableMaxTime, 'H:m');
    momentMaxTime.add(30, 'minutes');

    jQuery('#startAt').datetimepicker({
	minTime: momentMinTime.toDate(),
	maxTime: momentMaxTime.toDate(),
    });
    jQuery('#endAt').datetimepicker({
	minTime: momentMinTime.toDate(),
	maxTime: momentMaxTime.toDate(),
    });
    
    $("#startAt").removeAttr('disabled');
    $("#endAt").removeAttr('disabled');
    $('#allDay').removeAttr('disabled');
}

$(document).ready(function () {
    $("#btnSave").click(function () {
	$("#addBookingForm").submit();
    });

    var id = $("#bookableId").val();
    if (id == '') {	
	$("#startAt").attr('disabled', 'disabled');
	$("#endAt").attr('disabled', 'disabled');
	$('#allDay').attr('disabled', 'disabled');
    }

    $('#startAt').datetimepicker({
	datepicker: true,
	timepicker: true,
	format: 'Y-m-d H:i',
	formatDate: 'Y-m-d',
	formatTime: 'H:i',
	step: 30,
    });

    $('#endAt').datetimepicker({
	datepicker: true,
	timepicker: true,
	format: 'Y-m-d H:i',
	formatDate: 'Y-m-d',
	formatTime: 'H:i',
	step: 30,
    });


    $('#allDay').change(function () {
	var startVal = $('#startAt').val();
	var endVal = $('#endAt').val();
	var momentMinTime = new moment(bookableMinTime, 'H:m');
	var momentMaxTime = new moment(bookableMaxTime, 'H:m');
	
	if (startVal == '') {
	    startVal = new moment();
	} else {
	    startVal = new moment(startVal, 'YYYY-MM-DD H:m');
	}

	if (endVal == '') {
	    endVal = new moment();
	} else {
	    endVal = new moment(endVal, 'YYYY-MM-DD H:m');
	}
	
	startVal.set({hour: momentMinTime.hour(), minute: momentMinTime.minutes()});
	endVal.set({hour: momentMaxTime.hour(), minute: momentMaxTime.minutes()});

	if ($(this).is(':checked')) {
	    jQuery('#startAt').datetimepicker({
		timepicker: false,
		defaultTime: momentMinTime.toDate(),
		minTime: momentMinTime.toDate(),
		maxTime: momentMaxTime.toDate(),
		value: startVal.toDate(),
	    });

	    jQuery('#endAt').datetimepicker({
		timepicker: false,
		defaultTime: momentMaxTime.toDate(),
		minTime: momentMinTime.toDate(),
		maxTime: momentMaxTime.toDate(),
		value: endVal.toDate(),
	    });
	} else {
	    jQuery('#startAt').datetimepicker({
		datepicker: true,
		timepicker:true,
		defaultTime: 0,		
		minTime: momentMinTime.toDate(),
		maxTime: momentMaxTime.toDate(),
		value: startVal.toDate(),
	    });

	    jQuery('#endAt').datetimepicker({
		datepicker: true,
		timepicker:true,
		defaultTime: 0,
		minTime: momentMinTime.toDate(),
		maxTime: momentMaxTime.toDate(),
		value: endVal.toDate(),
	    });
	}
    });
});
