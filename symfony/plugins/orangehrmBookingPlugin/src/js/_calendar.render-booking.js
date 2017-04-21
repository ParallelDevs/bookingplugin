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
        element.addClass('booking');

        if (view) {
            switch (view.type) {
                case "timelineMonth":
                case "filterDatesAdmin":
                    element.find(".fc-time").text(event.duration + 'h p/d');
                    element.find(".fc-title").remove();
                    break;
                case "timelineWeek":
                    element.find(".fc-time").text(event.duration + 'h p/d');
                    break;
                case "month":
                case "basicWeek":
                    element.find(".fc-time").text(event.duration + 'h p/d');
                    break;
                default:
                    break;
            }
        }
    }

}

function eventMouseoverHandler(event, jsEvent, view) {
    $(this).addClass('fc-highlighted');
}

function eventMouseoutHandler(event, jsEvent, view) {
    $(this).removeClass('fc-highlighted');
}
