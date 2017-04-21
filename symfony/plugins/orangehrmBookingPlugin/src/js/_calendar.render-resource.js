function resourceErrorHandler() {

}
function resourceRenderHandler(resourceObj, labelTds, bodyTds) {
    $(".fc-resource-area.fc-widget-header").remove();
    $(".fc-resource-area.fc-widget-content").remove();
    $(".fc-col-resizer").remove();
}

function resourceRenderAdminHandler(resourceObj, labelTds, bodyTds) {
    if (resourceObj.isActive) {
        labelTds.addClass('booking-resource-active');
    } else {
        labelTds.addClass('booking-resource-inactive');
        labelTds.tipTip({
            content: inactiveResourceTooltip
        });
        
        if (!jQuery.isEmptyObject(bodyTds)) {
            bodyTds.addClass('fc-nonbusiness');
        }
    }
}
