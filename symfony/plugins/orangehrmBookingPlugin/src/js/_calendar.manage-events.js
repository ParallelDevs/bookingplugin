var bookingId = '';
var bookableId = '';
var bookableName = '';
var startDate = '';
var endDate = '';
var minStartTime = '';
var maxEndTime = '';
var holidayEvent = null;

function loadVarsFromEvent(event) {
    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    bookingId = event.id;
    startDate = event.start.format('YYYY-MM-DD');
    endDate = event.end.format('YYYY-MM-DD');
    bookableId = resource.id;
    bookableName = resource.title;
    minStartTime = resource.businessHours[0].start;
    maxEndTime = resource.businessHours[0].end;
}

function revertCalendar(revertFunc) {
    if (jQuery.isFunction(revertFunc)) {
        revertFunc();
    } else {
        refreshBookings();
    }
    holidayEvent = null;

}

function addBookingConfirmBusinessDays(start, end, resourceId) {
    var resource = $('#calendar').fullCalendar('getResourceById', resourceId);
    var startInBusinessDays = jQuery.inArray(start.day(), resource.businessHours[0].dow) >= 0 ? true : false;
    var endInBusinessDays = jQuery.inArray(end.day(), resource.businessHours[0].dow) >= 0 ? true : false;
    var startInHoliday = (holidayEvent && start.isSame(holidayEvent.start, 'day')) ? true : false;
    var endInHoliday = (holidayEvent && end.isSame(holidayEvent.start, 'day')) ? true : false;
    var msgConfirm = '';

    if (!startInBusinessDays) {
        msgConfirm = confirmStartBookingNonBusiness;
    } else if (!endInBusinessDays) {
        msgConfirm = confirmEndBookingNonBusiness;
    } else if (startInHoliday || endInHoliday) {
        msgConfirm = confirmBookingHoliday;
    }

    if (!startInHoliday && !endInHoliday && startInBusinessDays && endInBusinessDays) {
        ajaxLoadNewBooking();
    } else {
        if (confirm(msgConfirm)) {
            ajaxLoadNewBooking();
        } else {
            $('#calendar').fullCalendar('unselect');
            holidayEvent = null;
        }
    }
}

function editBookingConfirmNonBusinessDays(event, revertFunc) {
    var start = event.start;
    var end = event.end;
    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    var startInBusinessDays = jQuery.inArray(start.day(), resource.businessHours[0].dow) >= 0 ? true : false;
    var endInBusinessDays = jQuery.inArray(end.day(), resource.businessHours[0].dow) >= 0 ? true : false;
    var startInHoliday = (holidayEvent && start.isSame(holidayEvent.start, 'day')) ? true : false;
    var endInHoliday = (holidayEvent && end.isSame(holidayEvent.start, 'day')) ? true : false;
    var msgConfirm = '';

    if (!startInBusinessDays) {
        msgConfirm = confirmStartBookingNonBusiness;
    } else if (!endInBusinessDays) {
        msgConfirm = confirmEndBookingNonBusiness;
    } else if (startInHoliday || endInHoliday) {
        msgConfirm = confirmBookingHoliday;
    }

    if (!startInHoliday && !endInHoliday && startInBusinessDays && endInBusinessDays) {
        ajaxLoadEditBooking(revertFunc);
    } else {
        if (confirm(msgConfirm)) {
            ajaxLoadEditBooking(revertFunc);
        } else {
            revertCalendar(revertFunc);
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

function eventOverlapHandler(stillEvent, movingEvent) {
    if (stillEvent.isHoliday) {
        holidayEvent = stillEvent;
    } else {
        holidayEvent = null;
    }
    return true;
}

function selectHandler(start, end, jsEvent, view, resource) {
    bookableId = resource.id;
    bookableName = resource.title;
    minStartTime = resource.businessHours[0].start;
    maxEndTime = resource.businessHours[0].end;
    startDate = start.format('YYYY-MM-DD');

    var selectedEndDate = end.subtract(1, 'days');
    if (selectedEndDate.isBefore(startDate)) {
        selectedEndDate = start;
    }
    endDate = selectedEndDate.format('YYYY-MM-DD');

    addBookingConfirmBusinessDays(start, selectedEndDate, resource.id);
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
        holidayEvent = event;
    } else {
        holidayEvent = null;
    }
    return true;
}
