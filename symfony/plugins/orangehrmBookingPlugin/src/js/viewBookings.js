//=include _modals.js
//=include _calendar.options-admin.js

$(function () {
    calendarOptions.firstDay = firstDayOfWeek;
    calendarOptions.minTime = calendarMinTime;
    calendarOptions.maxTime = calendarMaxTime;
    calendarOptions.resources.url = bookableResourcesUrl;   
    calendarOptions.events.url = bookingResourcesUrl;    
    
    $('#calendar').fullCalendar(calendarOptions);
});
