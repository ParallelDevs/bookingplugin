var bookingId = '';
var bookableId = 0;
var bookableName = '';
var bookingStart = moment();
var bookingEnd = moment();
var bookingAllDay = false;

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
	    if (jQuery.inArray(start.day(), resource.businessHours[0].dow) < 0) {
		flag = false;
	    }
	}
    }
    return flag;
}

function selectOverlapHandler(event) {
    bookableMinTime = '';
    if (event.isHoliday) {
	showNewBookingHolidayCollision(event.title);
	return false;
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
	console.log(bookableMinTime);
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

    fillBookingForm();
    showAddBookingForm();
}

function unselectHandler(view, jsEvent) {
    bookableMinTime = '';
    bookableMaxTime = '';
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
	datepicker: true,
	timepicker: true,
	format: 'Y-m-d H:i',
	formatDate: 'Y-m-d',
	formatTime: 'H:i',
	step: 30,
	minTime: momentMinTime.toDate(),
	maxTime: momentMaxTime.toDate(),
	value: bookingStart.toDate()
    });
    jQuery('#endAt').datetimepicker({
	datepicker: true,
	timepicker: true,
	format: 'Y-m-d H:i',
	formatDate: 'Y-m-d',
	formatTime: 'H:i',
	step: 30,
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
}