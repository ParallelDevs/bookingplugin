var bookingId = '';
var bookableId = '';
var bookableName = '';
var startDate = '';
var endDate = '';
var minStartTime = '';
var maxEndTime = '';
var holidayEvent = null;
var scheduledTime = 0;
var workingTime = 0;

function loadVarsFromEvent (event) {
  var resource = $('#calendar').fullCalendar('getResourceById', event.resourceId);
  bookingId = event.id;
  startDate = event.start.format('YYYY-MM-DD');
  endDate = event.end.format('YYYY-MM-DD');
  bookableId = resource.id;
  bookableName = resource.title;
  minStartTime = resource.businessHours[0].start;
  maxEndTime = resource.businessHours[0].end;
  workingDays = resource.businessHours[0].dow;
  workingTime = getResourceWorkingTime(event.resourceId);
}

function revertCalendar (revertFunc) {
  if (jQuery.isFunction(revertFunc)) {
    revertFunc();
  } else {
    refreshBookings();
  }
  holidayEvent = null;
  scheduledTime = 0;
  workingTime = 0;
}

function addBookingConfirmNonBusinessDays (start, end, resourceId) {
  var resource = $('#calendar').fullCalendar('getResourceById', resourceId);
  var startInBusinessDays = jQuery.inArray(start.day(), resource.businessHours[0].dow) >= 0 ? true : false;
  var endInBusinessDays = jQuery.inArray(end.day(), resource.businessHours[0].dow) >= 0 ? true : false;
  var startInHoliday = (holidayEvent && start.isSame(holidayEvent.start, 'day')) ? true : false;
  var endInHoliday = (holidayEvent && end.isSame(holidayEvent.start, 'day')) ? true : false;
  var overScheduled = (scheduledTime >= workingTime) ? true : false;
  var msgConfirm = '';

  if (!startInBusinessDays) {
    msgConfirm = confirmStartBookingNonBusiness;
  } else if (!endInBusinessDays) {
    msgConfirm = confirmEndBookingNonBusiness;
  } else if (startInHoliday || endInHoliday) {
    msgConfirm = confirmBookingHoliday;
  } else if (overScheduled) {
    msgConfirm = confirmOverScheduling;
  }

  if (!startInHoliday && !endInHoliday && startInBusinessDays && endInBusinessDays && !overScheduled) {
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

function editBookingConfirmNonBusinessDays (event, revertFunc) {
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

function eventAfterRenderHandler (event, element, view) {
  if (event.isHoliday) {
    var date = event.start.format('YYYY-MM-DD');
    if (jQuery.inArray(date, holidays) < 0) {
      holidays.push(date);
    }
  }
  if (event.editable) {
    element.bind('dblclick', function () {
      eventDblClickHandler(event);
    });
  }
}

function eventResizeHandler (event, delta, revertFunc, jsEvent, ui, view) {
  loadVarsFromEvent(event);
  editBookingConfirmNonBusinessDays(event, revertFunc);
}

function eventDropHandler (event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
  var delta = dayDelta.asDays();
  if (0 === delta) {
    return;
  }

  loadVarsFromEvent(event);
  editBookingConfirmNonBusinessDays(event, revertFunc);
}

function eventDblClickHandler (event) {
  loadVarsFromEvent(event);
  ajaxLoadEditBooking(refreshBookings);
}

function eventOverlapHandler (stillEvent, movingEvent) {
  if (stillEvent.isHoliday) {
    holidayEvent = stillEvent;
  } else {
    holidayEvent = null;
  }
  return true;
}

function selectHandler (start, end, jsEvent, view, resource) {
  bookableId = resource.id;
  bookableName = resource.title;
  minStartTime = resource.businessHours[0].start;
  maxEndTime = resource.businessHours[0].end;
  workingDays = resource.businessHours[0].dow;
  startDate = start.format('YYYY-MM-DD');
  workingTime = getResourceWorkingTime(resource.id);

  var selectedEndDate = end.subtract(1, 'days');
  if (selectedEndDate.isBefore(startDate)) {
    selectedEndDate = start;
  }
  endDate = selectedEndDate.format('YYYY-MM-DD');

  addBookingConfirmNonBusinessDays(start, selectedEndDate, resource.id);
}

function selectAllowHandler (selectInfo) {
  var resource = $('#calendar').fullCalendar('getResourceById', selectInfo.resourceId);
  if (resource && !resource.isActive) {
    return false;
  }
  return true;
}

function selectOverlapHandler (event) {
  if (event.isHoliday) {
    holidayEvent = event;
  } else {
    holidayEvent = null;
  }
  return true;
}

function dayClickHandler (date, jsEvent, view, resourceObj) {
  scheduledTime = getResourceScheduledTime(resourceObj.id, date.format('YYYY-MM-DD'));
}

function getResourceScheduledTime (resourceId, date) {
  var resourceObj = $('#calendar').fullCalendar('getResourceById', resourceId);
  var time = 0;
  $('#calendar').fullCalendar('getResourceEvents', resourceObj).filter(function (event) {
    if (event.resourceId === resourceId && moment(date, 'YYYY-MM-DD').isBetween(event.start, event.end, 'day', '[]')) {
      var duration = Number(event.duration);
      if (!isNaN(duration)) {
        time += duration;
      }
      return true;
    }
    return false;
  });
  return time;
}

function getResourceWorkingTime (resourceId) {
  var resourceObj = $('#calendar').fullCalendar('getResourceById', resourceId);
  var start = moment(resourceObj.businessHours[0].start, "HH:mm");
  var end = moment(resourceObj.businessHours[0].end, "HH:mm");
  var d = moment.duration(end.diff(start));
  var hours = d.asHours();
  var minutes = d.asMinutes() - (hours * 60);
  minutes /= 60.0;
  return hours * 1.0 + minutes;
}
