//=include _modals.js
//=include _calendar.render-resource.js
//=include _calendar.render-booking.js
//=include _calendar.manage-booking.js
//=include _calendar.filter-booking.js
//=include _calendar.options-admin.js

$(function () {
  calendarOptions.firstDay = firstDayOfWeek;
  calendarOptions.minTime = calendarMinTime;
  calendarOptions.maxTime = calendarMaxTime;
  calendarOptions.resourceLabelText = bookableResourceTitle;
  calendarOptions.resources.url = bookableResourcesUrl;
  calendarOptions.events.url = bookingResourcesUrl;

  $("#calendar").fullCalendar(calendarOptions);
});
