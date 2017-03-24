var bookingId = '';
var bookableId = '';
var bookableName = '';
var startDate = '';
var endDate = '';
var minStartTime = '';
var maxEndTime = '';

function eventErrorHandler() {

}

function eventRenderHandler(event, element, view) {
    if (event.isHoliday) {
        element.tipTip({
            content: holidayLabel + ' ' + event.title
        });
        element.addClass('fc-nonbusiness booking-holiday');
    } else {
        element.tipTip({
            content: event.customerName + ' - ' + event.title
        });
        if (view) {
            switch (view.type) {
                case "timelineMonth":
                    element.find(".fc-time").text(event.duration + 'h p/d');
                    element.find(".fc-title").remove();
                    break;
                case "timelineWeek":
                    element.find(".fc-title").text(event.duration + 'h p/d');
                    break;
                case "timelineDay":
                    break;
                default:
                    break;
            }
        }
    }

}

function eventAfterRender(event, element, view) {
    //console.log(event,element,view);
    /*var $container = $element.parents('.fc-content-skeleton:first').find('.fc-event-container:last');
     var $totalRow = $container.find('.fc-day-grid-event.total');
     if ($totalRow.length == 0) {
     $totalRow = $('<a class="fc-day-grid-event total fc-h-event fc-event fc-start fc-end"><div class="fc-content"><span>Total: </span><span class="total-time"></span></div></a>')
     .appendTo($container);
     }
     var $total = $totalRow.find('.total-time');
     var total = parseFloat($totalRow.find('.total-time').text());
     total = isNaN(total) ? parseFloat(event.worktime) : total + parseFloat(event.worktime);
     $total.text(total);*/
}

function eventMouseoverHandler(event, jsEvent, view) {
    $(this).addClass('fc-highlighted');
}

function eventMouseoutHandler(event, jsEvent, view) {
    $(this).removeClass('fc-highlighted');
}

function eventResizeHandler(event, delta, revertFunc, jsEvent, ui, view) {
    bookingId = event.id;
    startDate = event.start.format('YYYY-MM-DD');
    endDate = event.end.format('YYYY-MM-DD');

    var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
    if (resource) {
        bookableId = resource.id;
        minStartTime = resource.businessHours[0].start;
        maxEndTime = resource.businessHours[0].end;
    }

    $.ajax({
        type: 'POST',
        url: bookingFormUrl,
        data: {
            "bookingId": bookingId,
            "bookableId": bookableId,
            "startDate": startDate,
            "endDate": endDate,
            "minStartTime": minStartTime,
            "maxEndTime": maxEndTime
        },
        success: function (response) {
            $('#editBooking').find('.modal-body').html(response);
            initModalFields();
            $('#editBooking').modal('show');
        },
        fail: function () {
            revertFunc();
        }
    });
}


function eventDropHandler(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
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

    $.ajax({
        type: 'POST',
        url: bookingFormUrl,
        data: {
            "bookingId": bookingId,
            "bookableId": bookableId,            
            "startDate": startDate,
            "endDate": endDate,
            "minStartTime": minStartTime,
            "maxEndTime": maxEndTime
        },
        success: function (response) {
            $('#editBooking').find('.modal-body').html(response);
            initModalFields();
            $('#editBooking').modal('show');
        },
        fail: function () {
            revertFunc();
        }
    });
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

   $.ajax({
        type: 'POST',
        url: bookingFormUrl,
        data: {
            "bookableId": bookableId,
            "bookableName": bookableName,
            "startDate": startDate,
            "endDate": endDate,
            "minStartTime": minStartTime,
            "maxEndTime": maxEndTime
        },
        success: function (response) {
            $('#addBooking').find('.modal-body').html(response);
            initModalFields();
            $('#addBooking').modal('show');
        }
    });
}

function selectAllowHandler(selectInfo) {
    var resource = $('#calendar').fullCalendar('getResourceById', selectInfo.resourceId);
    if (resource && !resource.isActive) {
        return false;
    }
    return true;
}
