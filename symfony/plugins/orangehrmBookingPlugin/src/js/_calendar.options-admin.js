var calendarOptions = {
    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    now: moment().startOf('day'),
    defaultDate: moment().format('YYYY-MM-DD'),
    firstDay: 1,
    selectable: true,
    selectHelper: true,
    editable: true,
    eventResourceEditable: false,
    aspectRatio: 2.5,
    slotEventOverlap: false,
    minTime: '00:00:00',
    maxTime: '23:59:59',    
    header: {
        left: 'prev,next today filter',
        center: 'title',
        right: 'timelineMonth,timelineWeek'
    },
    defaultView: 'timelineMonth',
    resourceAreaWidth: '25%',
    resourceLabelText: '',
    resources: {
        url: '',
        type: 'POST',
        error: resourceErrorHandler,
    },
    events: {
        url: '',
        data: {
            mode: 'timeline',
        },
        type: 'POST',
        error: eventErrorHandler,
    },
    resourceRender: resourceRenderAdminHandler,
    eventRender: eventRenderHandler,
    eventAfterRender: eventAfterRenderHandler,
    eventMouseover: eventMouseoverHandler,
    eventMouseout: eventMouseoutHandler,
    eventResize: eventResizeHandler,
    eventDrop: eventDropHandler,
    eventOverlap: eventOverlapHandler,
    select: selectHandler,
    selectAllow: selectAllowHandler,
    selectOverlap: selectOverlapHandler,
    views: {
        timelineDay: {
            slotDuration: {
                days: 1
            }
        },
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
    $("#calendar").on('click', ".fc-timelineMonth-button, .fc-timelineWeek-button", function () {
        $("#searchStartDate").val('')
                .change();
        $("#searchEndDate").val('')
                .change();
    });
    $(".btn.filter").click(function(){
        filterBookings("timelineFilter");
    });
});