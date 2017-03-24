//=include _calendar.resources.js
//=include _calendar.events.js
//=include _modals.js

$(function () {
    $('#calendar').fullCalendar({
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        now: moment().startOf('day'),
        selectable: true,
        selectHelper: true,
        editable: true,        
        aspectRatio: 4.0,
        firstDay: firstDayOfWeek,
        slotEventOverlap: false,
        minTime: calendarMinTime,
        maxTime: calendarMaxTime,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'timelineDay,timelineWeek,timelineMonth'
        },
        defaultView: 'timelineMonth',
        resourceAreaWidth: '25%',
        resourceLabelText: bookableResourceTitle,
        resources: {
            url: bookableResourcesUrl,
            type: 'POST',
            error: resourceErrorHandler,
        },
        events: {
            url: bookingResourcesUrl,
            data: {
                mode: 'timeline',
            },
            type: 'POST',
            error: eventErrorHandler,
        },
        resourceRender: resourceRenderHandler,
        eventRender: eventRenderHandler,
        eventMouseover: eventMouseoverHandler,
        eventMouseout: eventMouseoutHandler,
        eventResize: eventResizeHandler,
        eventDrop: eventDropHandler,
	select: selectHandler,
        selectAllow: selectAllowHandler,
    });
});
