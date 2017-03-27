var bookingId = '';
var bookableId = '';
var bookableName = '';
var startDate = '';
var endDate = '';
var minStartTime = '';
var maxEndTime = '';

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

function eventAfterRenderHandler(event, element, view) {    
    if (event.editable) {
        element.bind('dblclick', function () {
            eventDblClickHandler(event);
        });
    }
}

function eventResizeHandler(event, delta, revertFunc, jsEvent, ui, view) {
    loadVarsFromEvent(event);
    ajaxLoadEditBooking(revertFunc);
}


function eventDropHandler(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
    var delta = dayDelta.asDays();
    if (0 === delta) {
        return;
    }

    loadVarsFromEvent(event);
    ajaxLoadEditBooking(revertFunc);
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

    ajaxLoadNewBooking();
}

function selectAllowHandler(selectInfo) {
    var resource = $('#calendar').fullCalendar('getResourceById', selectInfo.resourceId);
    if (resource && !resource.isActive) {
        return false;
    }
    return true;
}


