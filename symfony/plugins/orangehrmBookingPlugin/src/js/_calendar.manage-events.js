var bookingId = '';
var bookableId = '';
var bookableName = '';
var startDate = '';
var endDate = '';
var minStartTime = '';
var maxEndTime = '';
var holidayOverlap = false;

function loadVarsFromEvent(event) {
    bookingId = event.id;
    startDate = event.start.format('YYYY-MM-DD');
    endDate = event.end.format('YYYY-MM-DD');

    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    if (resource) {
        bookableId = resource.id;
        bookableName = resource.title;
        minStartTime = resource.businessHours[0].start;
        maxEndTime = resource.businessHours[0].end;
    }
}

function editBookingConfirmNonBusinessDays(event, revertFunc) {
    var start = event.start;
    var end = event.end;
    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);

    if (!holidayOverlap && jQuery.inArray(start.day(), resource.businessHours[0].dow) >= 0 && jQuery.inArray(end.day(), resource.businessHours[0].dow) >= 0) {
        ajaxLoadEditBooking(revertFunc);
    } else if (holidayOverlap || jQuery.inArray(start.day(), resource.businessHours[0].dow) < 0) {
        if (confirm(confirmStartBookingNonBusiness)) {
            ajaxLoadEditBooking(revertFunc);
        } else {
            revertFunc();
            holidayOverlap = false;
        }
    } else if (holidayOverlap || jQuery.inArray(end.day(), resource.businessHours[0].dow) < 0) {
        if (confirm(confirmEndBookingNonBusiness)) {
            ajaxLoadEditBooking(revertFunc);
        } else {
            revertFunc();
            holidayOverlap = false;
        }
    }
}

function eventAfterRenderHandler(event, element, view) {
    if (event.editable) {
        element.bind('dblclick', function () {
            eventDblClickHandler(event);
        });
    }
}

function eventResizeHandler(event, delta, revertFunc, jsEvent, ui, view) {
    loadVarsFromEvent(event);
    editBookingConfirmNonBusinessDays(event, revertFunc);
}

function eventDropHandler(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
    var delta = dayDelta.asDays();
    if (0 === delta) {
        return;
    }

    loadVarsFromEvent(event);
    editBookingConfirmNonBusinessDays(event, revertFunc);
}

function eventDblClickHandler(event) {
    loadVarsFromEvent(event);
    ajaxLoadEditBooking(refreshBookings);
}

function selectHandler(start, end, jsEvent, view, resource) {
    if (resource) {
        bookableId = resource.id;
        bookableName = resource.title;
        minStartTime = resource.businessHours[0].start;
        maxEndTime = resource.businessHours[0].end;
    }

    startDate = start.format('YYYY-MM-DD');
    var selectedEndDate = end.subtract(1, 'days');
    if (selectedEndDate.isBefore(startDate)) {
        selectedEndDate = start;
    }
    endDate = selectedEndDate.format('YYYY-MM-DD');

    if (!holidayOverlap && jQuery.inArray(start.day(), resource.businessHours[0].dow) >= 0 && jQuery.inArray(selectedEndDate.day(), resource.businessHours[0].dow) >= 0) {
        ajaxLoadNewBooking();
    } else if (holidayOverlap || jQuery.inArray(start.day(), resource.businessHours[0].dow) < 0) {
        if (confirm(confirmStartBookingNonBusiness)) {
            ajaxLoadNewBooking();
        } else {
            $('#calendar').fullCalendar('unselect');
            holidayOverlap = false;
        }
    } else if (holidayOverlap || jQuery.inArray(selectedEndDate.day(), resource.businessHours[0].dow) < 0) {
        if (confirm(confirmEndBookingNonBusiness)) {
            ajaxLoadNewBooking();
        } else {
            $('#calendar').fullCalendar('unselect');
            holidayOverlap = false;
        }
    }

}

function selectAllowHandler(selectInfo) {
    var resource = $('#calendar').fullCalendar('getResourceById', selectInfo.resourceId);
    if (resource && !resource.isActive) {
        return false;
    }
    return true;
}

function selectOverlapHandler(event) {
    if (event.isHoliday) {
        holidayOverlap = true;
    } else {
        holidayOverlap = false;
    }
    return true;
}
