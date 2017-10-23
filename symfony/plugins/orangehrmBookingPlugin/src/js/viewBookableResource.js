$(document).ready(function () {
  $(".editable").each(function () {
    $(this).attr("disabled", "disabled");
  });


  $("#btnSave").click(function () {
    if ($("#btnSave").attr('value') == edit) {
      $(".editable").each(function () {
        $(this).removeAttr("disabled");
      });

      $("#btnSave").attr('value', save);
      return;
    }

    if ($("#btnSave").attr('value') == save) {
      $("#frmBookable").submit();
    }
  });

});


