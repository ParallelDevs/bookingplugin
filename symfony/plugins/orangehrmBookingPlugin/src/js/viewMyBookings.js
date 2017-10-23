//=include _calendar.render-resource.js
//=include _calendar.render-booking.js
//=include _calendar.filter-booking.js
//=include _calendar.options-resource.js

$(function () { // document ready
  calendarOptions.firstDay = firstDayOfWeek;
  calendarOptions.minTime = calendarMinTime;
  calendarOptions.maxTime = calendarMaxTime;
  calendarOptions.resources.url = bookableResourcesUrl;
  calendarOptions.resources.data.bookableId = bookableId;
  calendarOptions.events.url = bookingResourcesUrl;
  calendarOptions.events.data.bookableId = bookableId;

  $('#calendar').fullCalendar(calendarOptions);
});
