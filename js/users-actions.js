(function($) {
  "use strict"; // Start of use strict

  function getUsersID() {
    var data = [];
    $(".usercheck:checked").each(function () {
      data.push($(this).data("id"));
    });
    return data;
  }

  $(".actionBtn").on('click', function (event) {
    event.preventDefault();

    if(getUsersID().length === 0 && $(this).data("action") === 'delete')
      alert("Select user !");
    else if($("#sid").val() === "" && $(this).data("action") === 'add')
        alert("Empty SteamID !");
    else {
      $.post("includes/ajax/UsersActionHandler.php", {data: getUsersID(), action: $(this).data("action"), sid: $("#sid").val()}, function (data) {
        alert(data);
      });
      location.reload();
    }
  });

})(jQuery); // End of use strict