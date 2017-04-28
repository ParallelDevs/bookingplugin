function filterBookings() {
    var startDate = moment($("#searchStartDate").val(), "YYYY-MM-DD", true);
    var endDate = moment($("#searchEndDate").val(), "YYYY-MM-DD", true);

    if (startDate.isValid() && endDate.isValid() && endDate.isSameOrAfter(startDate)) {
        var diffDays = endDate.add(1, 'days')
                .diff(startDate, 'days');
        calendarOptions.views.timelineFilter.duration.days = diffDays;
        $("#calendar").fullCalendar('destroy');
        $("#calendar").fullCalendar(calendarOptions);
        $("#calendar").fullCalendar('changeView', 'timelineFilter', startDate);
    }
}

function changeSearchStartDate() {
    if ($("#searchEndDate").val() === '') {
        var startDate = moment($(this).val(), "YYYY-MM-DD", true);
        var endDate = '';
        var currentView = $("#calendar").fullCalendar('getView');
        switch (currentView.name) {
            case "timelineMonth":
                endDate = startDate.add(1, 'month')
                        .format("YYYY-MM-DD");
                break;
            case "timelineWeek":
                endDate = startDate.add(1, 'week')
                        .format("YYYY-MM-DD");
                break;
            default:
                endDate = startDate.format("YYYY-MM-DD");
                break;
        }
        $("#searchEndDate").val(endDate);
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