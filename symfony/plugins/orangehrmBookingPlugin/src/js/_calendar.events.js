function eventError() {

}

function eventRender(event, element, view) {
    console.log(view);
    if (event && element) {
        if (event.isHoliday) {
            element.tipTip({
                content: holidayLabel + ' ' + event.title
            });
            element.addClass('fc-nonbusiness booking-holiday');
        } else {            
            element.find(".fc-time").text(event.duration+'h p/d');
            element.find(".fc-title").remove();
            element.tipTip({
                content: event.customerName + ' - ' + event.title,
            });
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
