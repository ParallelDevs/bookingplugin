$(document).ready(function () {
    $('#calendar').fullCalendar({
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        now: currentDate,
        selectable: true,
        selectHelper: true,
        editable: true,
        aspectRatio: 4.0,
        firstDay: firstDayOfWeek,
        weekNumbers: true,
        slotEventOverlap: false,
        minTime: calendarMinTime,
        maxTime: calendarMaxTime,
        header: {
            left: 'today prev,next',
            center: 'title',
            right: 'timelineDay,timelineWeek,timelineMonth'
        },
        defaultView: 'timelineMonth',
        resourceAreaWidth: '25%',
        resourceLabelText: bookableResourceTitle,
        resources: {
            url: bookableResourcesUrl,
            type: 'POST',
            //error: errorResourceHandler,
        },
        events: {
            url: bookingResourcesUrl,
            data: {
                mode: 'timeline',
            },
            type: 'POST',
            //error: errorEventHandler,
        },
        /*resourceRender: resourceRenderHandler,
        eventRender: renderEventHandler,
        eventMouseover: eventMouseoverHandler,
        eventMouseout: eventMouseoutHandler,
        eventResize: eventResizeHandler,
        eventOverlap: eventOverlapHandler,
        eventClick: eventClickHandler,
        eventAllow: eventAllowHandler,
        selectAllow: selectAllowHandler,
        selectOverlap: selectOverlapHandler,
        select: selectHandler,
        unselect: unselectHandler,*/
    });
});
