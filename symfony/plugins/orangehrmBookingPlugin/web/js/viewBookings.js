var bookingId = '';
var bookableId = 0;
var bookableName = '';
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
		content: event.customer + ' - ' + event.title,
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

function selectAllowHandler(selectInfo) {
    var resource = null;
    var flag = true;
    var start = selectInfo.start;
    var end = selectInfo.end;
    if (selectInfo.resourceId) {
	resource = $('#calendar').fullCalendar('getResourceById', selectInfo.resourceId);
	if (resource) {
	    var momentMinTime = new moment(bookableMinTime, 'HH:m');
	    var momentMaxTime = new moment(bookableMaxTime, 'HH:m');

	    if (jQuery.inArray(start.day(), resource.businessHours[0].dow) < 0 ||
		    start.hours() < momentMinTime.hours() ||
		    start.hours() > momentMaxTime.hours() ||
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
    if (event.isHoliday) {
	holidayObj = event;
	return true;
    } else if (event.allday) {
	showNewBookingAllDayCollision(event.title);
	return false;
    }
    bookableMinTime = event.end.format('HH:mm');
    return true;
}

function selectHandler(start, end, jsEvent, view, resource) {
    var selectedStart, selectedEnd;

    if (resource) {
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
	    showNewBookingHolidayCollision(holidayObj.title);
	    return false;
	}
    }

    fillBookingForm();
    showAddBookingForm();
}

function unselectHandler(view, jsEvent) {
    bookableMinTime = '';
    bookableMaxTime = '';
    holidayObj = '';
}

/* notification functions*/

function showNewBookingAllDayCollision(name) {
    $("#bookingCollisionName").empty().append(name);
    $('#newBookingAllDayCollisionDialog').modal('show');
}

function showNewBookingHolidayCollision(name) {
    $("#holidayCollisionName").empty().append(name);
    $('#newBookingHolidayCollisionDialog').modal('show');
}

function showAddBookingForm() {
    $("#bookingDialogTitle").empty().append(addBookingTitle);
    $('#bookingDialog').modal('show');
}

/* helper functions */

function fillBookingForm() {
    var momentMinTime = new moment(bookableMinTime, 'HH:mm');
    var momentMaxTime = new moment(bookableMaxTime, 'HH:mm');
    momentMaxTime.add(30, 'minutes');

    $('#bookingId').val(bookingId);
    $('#bookableId').val(bookableId);
    $('#bookableName').val(bookableName);

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
	$('#calendar').fullCalendar('refetchEvents');
    } else {
	var length = data.errors.length;
	for (var i = 0; i < length; i++) {
	    console.log(data.errors[i]);
	    // Show error
	}
    }
}