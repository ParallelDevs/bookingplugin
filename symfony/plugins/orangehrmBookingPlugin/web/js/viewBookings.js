var bookingId = '';
var bookableId = 0;
var bookableName = '';
var customerId = '';
var projectId = '';
var bookingStart = moment();
var bookingEnd = moment();
var bookingAllDay = false;
var holidayObj = '';

/* handlers for calendar */

function errorResourceHandler() {
}

function errorEventHandler() {
}

function renderEventHandler(event, element) {
    if (event && element) {
	if (event.isHoliday) {
	    element.tipTip({content: event.title});
	    element.addClass('fc-nonbusiness holiday');
	} else {
	    element.tipTip({
		content: event.customerName + ' - ' + event.title,
	    });
	}
    }
}

function eventMouseoverHandler(event, jsEvent, view) {
    $(this).addClass('fc-highlighted');
}

function eventMouseoutHandler(event, jsEvent, view) {
    $(this).removeClass('fc-highlighted');
}

function eventResizeHandler(event, delta, revertFunc, jsEvent, ui, view) {
    var start = event.start;
    var end = event.end;

    if (holidayObj !== '' && (
	    start.isSame(holidayObj.start, 'day') ||
	    end.isSame(holidayObj.start, 'day'))
	    ) {
	revertFunc();
	return;
    }

    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    if (resource) {
	var momentMinTime = new moment(resource.businessHours[0].start, 'HH:mm');
	var momentMaxTime = new moment(resource.businessHours[0].end, 'HH:mm');

	if (resource.isActive &&
		jQuery.inArray(start.day(), resource.businessHours[0].dow) >= 0 &&
		jQuery.inArray(end.day(), resource.businessHours[0].dow) >= 0 &&
		start.hours() >= momentMinTime.hours() &&
		start.hours() <= momentMaxTime.hours() &&
		end.hours() >= momentMinTime.hours() &&
		end.hours() <= momentMaxTime.hours()
		) {

	    loadDataFromEvent(event);
	    fillBookingForm();
	    showEditBookingForm();

	    $("#dialogCancel").click(function () {
		cleanBookingForm();
		revertFunc();
	    });
	} else {
	    revertFunc();
	}
    } else {
	revertFunc();
    }
}

function eventOverlapHandler(stillEvent, movingEvent) {
    holidayObj = '';
    if (stillEvent.isHoliday) {
	holidayObj = stillEvent;
	return true;
    } else if (movingEvent.allday || stillEvent.allday) {
	showAllDayCollisionDialog(movingEvent.customerName + '-' + movingEvent.title, stillEvent.customerName + '-' + stillEvent.title);
	return false;
    } else if (movingEvent.start.hours() === stillEvent.start.hours() || movingEvent.end.hours() === stillEvent.end.hours()) {
	showTimeCollisionDialog(movingEvent.customerName + '-' + movingEvent.title, stillEvent.customerName + '-' + stillEvent.title);
	return false;
    }
    return true;
}

function eventClickHandler(event, jsEvent, view) {
    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    if (resource && !resource.isActive) {
	return false;
    }
    $("#dialogCancel").click(function () {
	cleanBookingForm();
    });
    loadDataFromEvent(event);
    fillBookingForm();
    showEditBookingForm();
}

function selectAllowHandler(selectInfo) {
    var resource = null;
    var flag = true;
    var start = selectInfo.start;
    var end = selectInfo.end;
    if (selectInfo.resourceId) {
	resource = $('#calendar').fullCalendar('getResourceById', selectInfo.resourceId);
	if (resource) {
	    if (!resource.isActive ||
		    jQuery.inArray(start.day(), resource.businessHours[0].dow) < 0 ||
		    (holidayObj !== '' && start.isSame(holidayObj.start, 'day'))
		    ) {
		flag = false;
	    }
	}
    }
    return flag;
}

function selectOverlapHandler(event) {
    bookableMinTime = '';
    holidayObj = '';
    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    if (resource && !resource.isActive) {
	return false;
    }
    if (event.isHoliday) {
	holidayObj = event;
	enableBookingAllDay = true;
	return true;
    } else if (event.allday) {
	showNewBookingAllDayCollision(event.title);
	enableBookingAllDay = true;
	return false;
    }
    bookableMinTime = event.end.format('HH:mm');
    enableBookingAllDay = false;
    return true;
}

function selectHandler(start, end, jsEvent, view, resource) {
    var selectedStart, selectedEnd;

    if (resource) {
	if (!resource.isActive) {
	    return false;
	}
	bookableId = resource.id;
	bookableName = resource.title;
	bookableMinTime = bookableMinTime !== '' ? bookableMinTime : resource.businessHours[0].start;
	bookableMaxTime = resource.businessHours[0].end;
    } else {
	bookableMinTime = '00:00';
	bookableMaxTime = '23:00';
    }

    var momentMinTime = new moment(bookableMinTime, 'HH:m');
    var momentMaxTime = new moment(bookableMaxTime, 'HH:m');

    selectedStart = start.format('YYYY-MM-DD');
    if (start.hours() < momentMinTime.hours()) {
	selectedStart += ' ' + bookableMinTime;
    } else {
	selectedStart += ' ' + start.format('HH:mm');
    }

    var selectedEndDate = end.subtract(1, 'days');
    if (selectedEndDate.isBefore(selectedStart)) {
	selectedEndDate = start.clone();
	selectedEndDate.set({hour: end.hours(), minute: end.minutes()});
    }

    selectedEnd = selectedEndDate.format('YYYY-MM-DD');
    if (selectedEndDate.hours() < momentMinTime.hours()) {
	selectedEnd += ' ' + bookableMinTime;
    } else if (selectedEndDate.hours() > momentMaxTime.hours()) {
	selectedEnd += ' ' + bookableMaxTime;
    } else {
	selectedEnd += ' ' + end.format('HH:mm');
    }

    bookingStart = moment(selectedStart);
    bookingEnd = moment(selectedEnd);

    if (bookingEnd.isSame(bookingStart, 'minutes')) {
	bookingEnd.add(30, 'minutes');
    }

    if (holidayObj !== '') {
	var holidayStart = holidayObj.start.format('YYYY-MM-DD');
	if (bookingEnd.isSame(holidayStart, 'day')) {
	    cleanBookingForm();
	    showNewBookingHolidayCollision(holidayObj.title);
	    return false;
	}
    }

    fillBookingForm();
    showAddBookingForm();
}

function unselectHandler(view, jsEvent) {
    cleanBookingForm();
}

/* notification functions*/

function showNewBookingAllDayCollision(name) {
    cleanBookingForm();
    $("#bookingCollisionName").empty().append(name);
    $('#newBookingAllDayCollisionDialog').modal('show');
}

function showNewBookingHolidayCollision(name) {
    cleanBookingForm();
    $("#holidayCollisionName").empty().append(name);
    $('#newBookingHolidayCollisionDialog').modal('show');
}

function showAllDayCollisionDialog(name1, name2) {
    cleanBookingForm();
    $("#bookingColl1").empty().append(name1);
    $("#bookingColl2").empty().append(name2);
    $('#bookingAllDayCollisionDialog').modal('show');
}

function showTimeCollisionDialog(name1, name2) {
    cleanBookingForm();
    $("#bookingTimeColl1").empty().append(name1);
    $("#bookingTimeColl2").empty().append(name2);
    $('#bookingTimeCollisionDialog').modal('show');
}

function showAddBookingForm() {
    $("#bookingDialogTitle").empty().append(addBookingTitle);
    $('#bookingDialog').modal('show');
}

function showEditBookingForm() {
    $("#bookingDialogTitle").empty().append(editBookingTitle);
    $('#bookingDialog').modal('show');
}


/* helper functions */

function loadDataFromEvent(event) {
    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    if (resource) {
	bookableId = resource.id;
	bookableName = resource.title;
	bookableMinTime = resource.businessHours[0].start;
	bookableMaxTime = resource.businessHours[0].end;
    }
    bookingId = event.id ? event.id : '';
    customerId = event.customerId ? event.customerId : '';
    projectId = event.projectId ? event.projectId : '';
    bookingStart = moment(event.start.format('YYYY-MM-DD HH:mm'));
    bookingEnd = moment(event.end.format('YYYY-MM-DD HH:mm'));
    bookingAllDay = event.allday;
}

function fillBookingForm() {
    var momentMinTime = new moment(bookableMinTime, 'HH:mm');
    var momentMaxTime = new moment(bookableMaxTime, 'HH:mm');
    momentMaxTime.add(30, 'minutes');

    $('#bookingId').val(bookingId);
    $('#bookableId').val(bookableId);
    $('#bookableName').val(bookableName);

    if (customerId !== '') {
	$("#customerId").val(customerId).change();
    }

    if (projectId !== '') {
	$("#projectId").val(projectId).change();
    }

    jQuery('#startAt').datetimepicker({
	minTime: momentMinTime.toDate(),
	maxTime: momentMaxTime.toDate(),
	value: bookingStart.toDate()
    });

    jQuery('#endAt').datetimepicker({
	minTime: momentMinTime.toDate(),
	maxTime: momentMaxTime.toDate(),
	value: bookingEnd.toDate()
    });

    $('#allDay').prop('checked', bookingAllDay);    
}

function cleanBookingForm() {
    $('#bookingId').val('');
    $('#bookableId').val('');
    $('#bookableName').val('');
    $('#startAt').val('');
    $('#endAt').val('');
    bookingId = '';
    bookableId = 0;
    bookableName = '';
    customerId = '';
    projectId = '';
    bookingStart = moment();
    bookingEnd = moment();
    bookableMinTime = '';
    bookableMaxTime = '';
    holidayObj = '';
}

function successBookingForm(data) {
    if (data.success) {
	$('#bookingDialog').modal('hide');
	cleanBookingForm();
	$("#dialogCancel").click(function () {
	    cleanBookingForm();
	});
	$('#calendar').fullCalendar('refetchEvents');
    } else {
	var length = data.errors.length;
	for (var i = 0; i < length; i++) {
	    console.log(data.errors[i]);
	    // Show error
	}
    }
}

// document ready
$(function () {
    $('#calendar').fullCalendar({
	schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
	now: currentDate,
	selectable: true,
	selectHelper: true,
	editable: true,
	aspectRatio: 4.0,
	firstDay: 1,
	weekNumbers: true,
	slotEventOverlap: false,
	minTime: calendarMinTime,
	maxTime: calendarMaxTime,
	header: {
	    left: 'today prev,next',
	    center: 'title',
	    right: 'timelineDay,timelineWeek,timelineMonth'
	},
	defaultView: 'timelineMonth',
	resourceAreaWidth: '25%',
	resourceLabelText: bookableResourceTitle,
	resources: {
	    url: bookableResourcesUrl,
	    type: 'POST',
	    error: errorResourceHandler,
	},
	events: {
	    url: bookingResourcesUrl,
	    data: {
		mode: 'timeline',
	    },
	    type: 'POST',
	    error: errorEventHandler,
	},
	eventRender: renderEventHandler,
	eventMouseover: eventMouseoverHandler,
	eventMouseout: eventMouseoutHandler,
	eventResize: eventResizeHandler,
	eventOverlap: eventOverlapHandler,
	eventClick: eventClickHandler,	
	selectAllow: selectAllowHandler,
	selectOverlap: selectOverlapHandler,
	select: selectHandler,
	unselect: unselectHandler,	
    });

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
});