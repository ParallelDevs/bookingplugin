var calendarOptions = {
    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    now: moment().startOf('day'),
    selectable: false,
    selectHelper: false,
    editable: false,
    eventResourceEditable: false,
    aspectRatio: 5.0,
    firstDay: 1,
    slotEventOverlap: false,
    minTime: '00:00:00',
    maxTime: '23:59:59',
    businessHours: true,
    lazyFetching: false,
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'timelineMonth,timelineWeek'
    },
    defaultView: 'timelineMonth',
    resources: {
        url: '',
        data: {
            bookableId: 0,
        },
        type: 'POST',
        error: resourceErrorHandler,
    },
    events: {
        url: '',
        data: {
            bookableId: 0,
            mode: 'agenda',
        },
        type: 'POST',
        error: eventErrorHandler,
    },
    resourceRender: resourceRenderHandler,
    eventRender: eventRenderHandler,
    eventMouseover: eventMouseoverHandler,
    eventMouseout: eventMouseoutHandler,
    selectAllow: false,
    views: {
        timelineWeek: {
            slotDuration: {
                days: 1
            }
        },
        timelineFilter: {
            type: 'timeline',
            duration: {
                days: 1
            },
            slotDuration: {
                days: 1
            }
        }
    }
};

$(document).ready(function () {
    $("#calendar").on('click', ".fc-month-button, .fc-basicWeek-button, .fc-basicDay-button", function () {
        $("#searchStartDate").val('')
                .change();
        $("#searchEndDate").val('')
                .change();
    });

    $(".btn.filter").click(filterBookings);
});