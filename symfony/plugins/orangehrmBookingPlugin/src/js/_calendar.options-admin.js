//=include _calendar.render-resource.js
//=include _calendar.render-event.js
//=include _calendar.manage-events.js

var calendarOptions = {
    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    now: moment().startOf('day'),
    selectable: true,
    selectHelper: true,
    editable: true,
    eventResourceEditable: false,
    aspectRatio: 4.0,
    firstDay: 1,
    slotEventOverlap: false,
    minTime: '00:00:00',
    maxTime: '23:59:59',
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'timelineDay,timelineWeek,timelineMonth'
    },
    defaultView: 'timelineMonth',
    resourceAreaWidth: '25%',
    resourceLabelText: bookableResourceTitle,
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
};


