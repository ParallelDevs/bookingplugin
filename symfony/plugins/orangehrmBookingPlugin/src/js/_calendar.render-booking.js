function eventErrorHandler () {

}

function eventRenderHandler (event, element, view) {
  if (event.isHoliday) {
    element.qtip({
      content: holidayLabel + ' ' + event.title
    });
    element.addClass('fc-nonbusiness booking-holiday');
  } else {
    var days = event.end.diff(event.start, 'days');
    var totalDuration = event.duration;
    days += 1;
    totalDuration *= days;

    element.qtip({
      content: {
        text: 'Total: ' + totalDuration + 'h',
        title: event.customerName + ' - ' + event.title
      }
    });
    element.addClass('booking');

    if (view) {
      switch(view.type){
        case "timelineMonth":
        case "timelineWeek":
        case "timelineFilter":
        case "month":
        case "basicWeek":
          var duration = Number(event.duration);
          element.find(".fc-time").text(duration + 'h p/d');
          element.find(".fc-title").remove();
          break;
        default:
          break;
      }
    }
  }

}

function eventMouseoverHandler (event, jsEvent, view) {
  $(this).addClass('fc-highlighted');
}

function eventMouseoutHandler (event, jsEvent, view) {
  $(this).removeClass('fc-highlighted');
}

function eventDestroyHandler (event, element, view) {
  $(element).qtip('destroy', true);
}  
