jQuery(document).ready(function () {
      $("#bookableId").change(function () {
          var id = $(this).val();
          if (id != '') {
              $.ajax({
                  type: "POST",
                  url: bookableWorkShiftsUrl,
                  data: {bookableId: id},
                  cache: false,
                  success: function (data)
                  {
                      setBookableWorkShift(data);
                  }
              });
          }
      });

      if ($("#bookableId").val() !== '') {
          $("#bookableId").change();
      }

      if ($("#customerId").val() !== '') {
          $("#customerId").change();
      }
  });