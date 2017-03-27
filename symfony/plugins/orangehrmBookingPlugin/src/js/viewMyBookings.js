//=include _calendar.resources.js
//=include _calendar.events.js

$(function () { // document ready

    $('#calendar').fullCalendar({
	schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
	now: moment().startOf('day'),
	selectable: false,
	selectHelper: false,
	editable: false,
        eventResourceEditable: false,
	aspectRatio: 4.0,
	firstDay: firstDayOfWeek,
	slotEventOverlap: false,
        minTime: calendarMinTime,
        maxTime: calendarMaxTime,
	header: {
	    left: 'today prev,next',
	    center: 'title',
	    right: 'agendaWeek,month'
	},
	defaultView: 'month',
	resources: {
	    url: bookableResourcesUrl,
	    data: {
		bookableId: bookableId,
	    },
	    type: 'POST',
            error: resourceErrorHandler,
	},
	events: {
	    url: bookingResourcesUrl,
	    data: {
		bookableId: bookableId,
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
    });

});



