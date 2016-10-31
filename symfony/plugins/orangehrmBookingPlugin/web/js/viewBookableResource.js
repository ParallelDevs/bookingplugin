$(document).ready(function () {
    $(".editable").each(function () {
	$(this).attr("disabled", "disabled");
    });

    $("#bookableColor").spectrum({
	preferredFormat: "hex",
	disabled: true,
    });

    $("#btnSave").click(function () {
	if ($("#btnSave").attr('value') == edit) {
	    $(".editable").each(function () {
		$(this).removeAttr("disabled");
	    });
	    
	    $("#bookableColor").spectrum("enable");
	    $("#btnSave").attr('value', save);
            return;
	}
	
	if($("#btnSave").attr('value') == save) {
	    $("#bookableform").submit();
	}
    });

});


