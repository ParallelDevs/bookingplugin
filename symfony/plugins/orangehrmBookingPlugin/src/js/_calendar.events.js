function eventErrorHandler() {

}

function eventRenderHandler(event, element, view) {
    if (event.isHoliday) {
        element.tipTip({
            content: holidayLabel + ' ' + event.title
        });
        element.addClass('fc-nonbusiness booking-holiday');
    } else {
        element.tipTip({
            content: event.customerName + ' - ' + event.title
        });
        if (view) {
            switch (view.type) {
                case "timelineMonth":
                    element.find(".fc-time").text(event.duration + 'h p/d');
                    element.find(".fc-title").remove();
                    break;
                case "timelineWeek":
                    element.find(".fc-title").text(event.duration + 'h p/d');
                    break;
                case "timelineDay":
                    break;
                default:
                    break;
            }
        }
    }

}

function eventAfterRender(event, element, view) {
    //console.log(event,element,view);
    /*var $container = $element.parents('.fc-content-skeleton:first').find('.fc-event-container:last');
     var $totalRow = $container.find('.fc-day-grid-event.total');
     if ($totalRow.length == 0) {
     $totalRow = $('<a class="fc-day-grid-event total fc-h-event fc-event fc-start fc-end"><div class="fc-content"><span>Total: </span><span class="total-time"></span></div></a>')
     .appendTo($container);
     }
     var $total = $totalRow.find('.total-time');
     var total = parseFloat($totalRow.find('.total-time').text());
     total = isNaN(total) ? parseFloat(event.worktime) : total + parseFloat(event.worktime);
     $total.text(total);*/
}

function eventMouseoverHandler(event, jsEvent, view) {
    $(this).addClass('fc-highlighted');
}

function eventMouseoutHandler(event, jsEvent, view) {
    $(this).removeClass('fc-highlighted');
}

function eventResizeHandler() {}
function eventOverlapHandler() {}
function eventClickHandler() {}
function eventAllowHandler() {}
function selectAllowHandler() {}
function selectOverlapHandler() {}
function selectHandler() {}
function unselectHandler() {}