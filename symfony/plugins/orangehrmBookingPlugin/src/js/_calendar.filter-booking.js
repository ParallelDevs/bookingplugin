function filterBookings() {
    var startDate = $("#searchStartDate").val();
    var endDate = $("#searchEndDate").val();

    if (!jQuery.isEmptyObject(startDate) && !jQuery.isEmptyObject(endDate)) {
        startDate = moment($("#searchStartDate").val(), 'YYYY-MM-DD');
        endDate = moment($("#searchEndDate").val(), 'YYYY-MM-DD');
        var diffDays = endDate.add(1, 'days').diff(startDate, 'days');
        calendarOptions.views.filterDates.duration.days = diffDays;
        $('#calendar').fullCalendar('destroy');
        $('#calendar').fullCalendar(calendarOptions);
        $('#calendar').fullCalendar('changeView', 'filterDates', startDate);
    }

}

$(document).ready(function(){
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