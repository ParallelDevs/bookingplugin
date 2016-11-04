var bookableMinTime = '';
var bookableMaxTime = '';

function fillProjectSelect(id, data) {
    var $select = $(id);
    $select.find('option').remove();
    $.each(data, function (key, value) {
	$('<option>').val(value.projectId).text(value.name).appendTo($select);
    });
}

function resourceRenderHandler(resourceObj, labelTds, bodyTds) {
    if (!resourceObj.isActive) {
	labelTds.addClass('fc-nonbusiness inactive-resource');
	labelTds.tipTip({
	    content: inactiveResourceTooltip
	});
	if (!jQuery.isEmptyObject(bodyTds)) {
	    bodyTds.addClass('fc-nonbusiness');
	}
    }
}

function errorResourceHandler() {
}

function errorEventHandler() {
}

function renderEventHandler(event, element) {
    if (event && element) {
	if (event.isHoliday) {
	    element.tipTip({
		content: holidayLabel + ' ' + event.title
	    });
	    element.addClass('fc-nonbusiness holiday');
	} else {
	    element.tipTip({
		content: event.customerName + ' - ' + event.title,
	    });
	}
    }
}

function eventMouseoverHandler(event, jsEvent, view) {
    $(this).addClass('fc-highlighted');
}

function eventMouseoutHandler(event, jsEvent, view) {
    $(this).removeClass('fc-highlighted');
}