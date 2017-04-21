function filterBookings(viewFilter) {
    var startDate = moment($("#searchStartDate").val(), "YYYY-MM-DD", true);
    var endDate = moment($("#searchEndDate").val(), "YYYY-MM-DD", true);

    if (startDate.isValid() && endDate.isValid() && endDate.isSameOrAfter(startDate)) {
        var diffDays = endDate.add(1, 'days')
                .diff(startDate, 'days');
        calendarOptions.views[viewFilter].duration.days = diffDays;
        $("#calendar").fullCalendar('destroy');
        $("#calendar").fullCalendar(calendarOptions);
        $("#calendar").fullCalendar('changeView', viewFilter,startDate);
    }
}

function changeSearchStartDate() {
    if ($("#searchEndDate").val() === '') {
        $("#searchEndDate").val($(this).val());
    }
}

$(document).ready(function () {
    jQuery("#searchStartDate").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
        formatDate: "Y-m-d",
        dayOfWeekStart: firstDayOfWeek
    });

    jQuery("#searchEndDate").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
        formatDate: "Y-m-d",
        dayOfWeekStart: firstDayOfWeek
    });

    $("#searchStartDate").change(changeSearchStartDate);
});