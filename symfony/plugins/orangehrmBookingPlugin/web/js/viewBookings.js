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
    bookableMinTime = event.end.format('H:mm');
    return true;
}

function selectHandler(start, end, jsEvent, view, resource) {
    console.log(view);
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

    var momentMinTime = new moment(bookableMinTime, 'H:m');
    var momentMaxTime = new moment(bookableMaxTime, 'H:m');

    selectedStart = start.format('YYYY-MM-DD');
    if(start.hours()<momentMinTime.hours()){
	selectedStart+=' '+bookableMinTime;
    }

    
    bookingStart = moment(selectedStart);
    bookingEnd = moment(selectedEnd);
    console.log(bookingStart.format('YYYY-MM-DD H:mm'), bookingEnd.format('YYYY-MM-DD H:mm'));

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
    var momentMinTime = new moment(bookableMinTime, 'hh:mm');
    var momentMaxTime = new moment(bookableMaxTime, 'hh:mm');
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
}