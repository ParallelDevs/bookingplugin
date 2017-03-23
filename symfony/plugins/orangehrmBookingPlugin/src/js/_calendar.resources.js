function resourceErrorHandler() {

}

function resourceRenderHandler(resourceObj, labelTds, bodyTds) {
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
