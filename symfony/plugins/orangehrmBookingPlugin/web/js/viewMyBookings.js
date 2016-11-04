$(function () { // document ready

    $('#calendar').fullCalendar({
	schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
	now: currentDate,
	selectable: false,
	selectHelper: false,
	editable: false,
	aspectRatio: 4.0,
	firstDay: 1,
	minTime: calendarMinTime,
	maxTime: calendarMaxTime,
	header: {
	    left: 'today prev,next',
	    center: 'title',
	    right: 'basicDay,agendaWeek,month'
	},
	defaultView: 'agendaWeek',
	resources: {
	    url: bookableResourcesUrl,
	    data: {
		bookableId: bookableId,
	    },
	    type: 'POST',
	},
	events: {
	    url: bookingResourcesUrl,
	    data: {
		bookableId: bookableId,
		mode: 'agenda',
	    },
	    type: 'POST',
	    error: errorEventHandler,
	},
	eventMouseover: eventMouseoverHandler,
	eventMouseout: eventMouseoutHandler,
	eventRender: renderEventHandler,
	selectAllow: false,
	selectOverlap: false,
	eventOverlap: false,
	eventResize: false,
    });

});


