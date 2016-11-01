var bookableMinTime = '';
var bookableMaxTime = '';

function fillProjectSelect(id, data) {
    var $select = $(id);
    $select.find('option').remove();
    $.each(data, function (key, value) {
	$('<option>').val(value.projectId).text(value.name).appendTo($select);
    });
}

