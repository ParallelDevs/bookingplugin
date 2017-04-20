//=include _modals.js
//=include _calendar.options-admin.js

$(function () {
    calendarOptions.firstDay = firstDayOfWeek;
    calendarOptions.minTime = calendarMinTime;
    calendarOptions.maxTime = calendarMaxTime;
    calendarOptions.resourceLabelText= bookableResourceTitle;
    calendarOptions.customButtons.filter.text = filterBtnLabel;
    calendarOptions.resources.url = bookableResourcesUrl;   
    calendarOptions.events.url = bookingResourcesUrl;    
    
    $('#calendar').fullCalendar(calendarOptions);
    jQuery("#searchStartDate").datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        dayOfWeekStart: firstDayOfWeek        
    });
    jQuery("#searchEndDate").datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        dayOfWeekStart: firstDayOfWeek        
    });
});
