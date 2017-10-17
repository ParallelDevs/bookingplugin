function eventErrorHandler () {

}

function eventRenderHandler (event, element, view) {
  if (event.isHoliday) {
    element.tipTip({
      content: holidayLabel + ' ' + event.title
    });
    element.addClass('fc-nonbusiness booking-holiday');
  } else {
    var days = event.end.diff(event.start, 'days');
    var totalDuration = event.duration;
    days += 1;
    totalDuration *= days;
    var tooltip = '<div class="booking-tooltip-title">' + event.customerName + ' - ' + event.title + '</div>';
    tooltip += '<div class="booking-tooltip-content">Total: ' + totalDuration + 'h' + '</div>';

    element.tipTip({
      content: tooltip
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
