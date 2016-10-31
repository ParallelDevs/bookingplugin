var eventFormReady = false;
var resourceId = 0;
var resourceName = '';
var startDate = '';
var endDate = '';
var startTime = '';
var bookingStartTime = '';

function errorResourceHandler() {
}

function errorEventHandler() {
}

function selectHandler(start, end, jsEvent, view, resource) {
    if (resource) {
	resourceId = resource.id;
	resourceName = resource.title;
	bookingStartTime = bookingStartTime !== '' ? bookingStartTime : resource.businessHours[0].start;
	/*$('#startTime').timepicker('option', 'minTime', minTime);
	$('#startTime').timepicker('option', 'maxTime', maxTime);
	$('#endTime').timepicker('option', 'minTime', minTime);
	$('#endTime').timepicker('option', 'maxTime', maxTime);*/
    }

    startDate = start.format('YYYY-MM-DD');
    var selectedEndDate = end.subtract(1, 'days');
    if (selectedEndDate.isBefore(startDate)) {
	selectedEndDate = start;
    }
    endDate = selectedEndDate.format('YYYY-MM-DD');
    startTime = bookingStartTime !== '' ? bookingStartTime : start.format('hh:mm a');
    fillAddBookingForm();
    showAddBookingForm();
}

function unselectHandler(view, jsEvent) {
    bookingStartTime = '';
}

function eventClickHandler(calEvent, jsEvent, view) {
}

function eventMouseoverHandler(event, jsEvent, view) {
    $(this).addClass('fc-highlighted');
}

function eventMouseoutHandler(event, jsEvent, view) {
    $(this).removeClass('fc-highlighted');
}

function renderEventHandler(event, element) {    
    if (event && element) {
	if (event.isHoliday) {
	    element.tipTip({content:event.title});
	    element.addClass('fc-nonbusiness holiday');
	} else {
	    element.tipTip({
		content: event.customer + ' - ' + event.title,
	    });
	}
    }
}

function selectAllowHandler(selectInfo) {
    var resource = null;
    var flag = true;
    var start = selectInfo.start;
    var end = selectInfo.end;
    if (selectInfo.resourceId) {
	resource = $('#calendar').fullCalendar('getResourceById', selectInfo.resourceId);
	if (resource) {
	    minTime = resource.businessHours[0].start;
	    maxTime = resource.businessHours[0].end;
	    if (jQuery.inArray(start.day(), resource.businessHours[0].dow) < 0) {
		flag = false;
	    }
	} else {
	    minTime = '00:00am';
	    maxTime = '11:59pm';
	}
    }

    return flag;
}

function selectOverlapHandler(event) {
    bookingStartTime = '';
    if (event.allday) {
	showNewBookingAllDayCollision(event.title);
	return false;
    }
    bookingStartTime = event.end.format('hh:mm a');
    return true;
}

function eventOverlapHandler(stillEvent, movingEvent) {
    if (stillEvent.allday || movingEvent.allday) {
	showAllDayCollisionDialog(movingEvent.customer + '-' + movingEvent.title, stillEvent.customer + '-' + stillEvent.title);
	return false;
    } else if (movingEvent.start.hours() === stillEvent.start.hours() || movingEvent.end.hours() === stillEvent.end.hours()) {
	showTimeCollisionDialog(movingEvent.customer + '-' + movingEvent.title, stillEvent.customer + '-' + stillEvent.title);
	return false;
    }
    return true;
}

function eventResizeHandler(event, delta, revertFunc, jsEvent, ui, view) {
    console.log('eventResizeHandler', event, delta, event.end.format());
    var end = event.end;
    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    if (resource) {
	if (jQuery.inArray(end.day(), resource.businessHours[0].dow) >= 0) {
	    console.log('end day is valid, event can be updated', end.day());
	} else {
	    revertFunc();
	}
    } else {
	revertFunc();
    }
}

function fillAddBookingForm() {
    $('#bookableId').val(resourceId);
    $('#bookableName').val(resourceName);
    $('#startDate').val(startDate);
    $('#endDate').val(endDate);
    $('#startTime').val(startTime).change();
    eventFormReady = true;
}

function cleanAddBookingForm() {
    $('#bookableId').val('');
    $('#bookableName').val('');
    $('#startDate').val('');
    $('#endDate').val('');
    $('#startTime').val('');
    $('#endTime').val('');
    eventFormReady = false;
    resourceId = 0;
    resourceName = '';
    startDate = '';
    endDate = '';
    startTime = '';
    bookingStartTime = '';
    minTime = '';
    maxTime = '';
}

function showAddBookingForm() {
    $('#bookingDialog').modal('show');
}

function showNewBookingAllDayCollision(name) {
    $("#bookingCollisionName").empty().append(name);
    $('#newBookingAllDayCollisionDialog').modal('show');
}

function showAllDayCollisionDialog(name1, name2) {
    $("#bookingColl1").empty().append(name1);
    $("#bookingColl2").empty().append(name2);
    $('#bookingAllDayCollisionDialog').modal('show');
}

function showTimeCollisionDialog(name1, name2) {
    $("#bookingTimeColl1").empty().append(name1);
    $("#bookingTimeColl2").empty().append(name2);
    $('#bookingTimeCollisionDialog').modal('show');
}

function successAddBooking(data) {
    if (data.success) {
	$('#bookingDialog').modal('hide');
	cleanAddBookingForm();
	$('#calendar').fullCalendar('refetchEvents');
    } else {
	var length = data.errors.length;
	for (var i = 0; i < length; i++) {
	    console.log(data.errors[i]);
	    // Show error
	}
    }
}