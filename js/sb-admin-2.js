(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

  $(window).on('load', function() {
    // Animate loader off screen
    $(".loader").fadeOut(300);
    $(".onload-animate-zoom").addClass("admin-animate-zoom");
    $(".onload-animate-left").addClass("admin-animate-left");
    $(".onload-animate-bottom").addClass("admin-animate-bottom");

    var pagePathName= window.location.pathname;
    var name = pagePathName.substring(pagePathName.lastIndexOf("/") + 1).replace(".php", "");
    $("#" + name).addClass("active");

    if(Cookies.get("coockie_alertsClicked") === "true") {
      $("#alertsCount").remove();
    }
  });

  $("#backToReceipts").on("click", function () {
    $(".loader").fadeIn(300);
    window.location.reload();
  });

  var formValidity = false;
  $("#pass2").on('keyup', function () {
    if($(this).val() === $("#pass1").val()) {
      $(this).removeClass("is-invalid");
      $(this).addClass("is-valid");
      $("#registerAccount").prop("disabled", false);
      formValidity = true;
    } else {
      $(this).removeClass("is-valid");
      $(this).addClass("is-invalid");
      $("#registerAccount").prop("disabled", true);
      formValidity = false;
    }
  });

  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false || formValidity === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);

  $("#alertsDropdown").on('click', function () {
      $("#alertsCount").remove();
      Cookies.set("coockie_alertsClicked", "true");
  });

  $(".usercheck").on('click', function () {
    if($(this).parents("tr").hasClass("table-success")) {
      $(this).parents("tr").removeClass("table-success");
  }
    else
      $(this).parents("tr").addClass("table-success");
  });

  $("#dropUser").on('click', function (event) {
      if(!confirm("Are you sure ?"))
        event.preventDefault();
  });

  $(".infoModal").on('click', function (event) {

    event.preventDefault();
    if(event.target.nodeName === "A" || event.target.nodeName === "I")
      return;

    $("tr").css("cursor", "progress");
    var id = $(this).find('.ban_id').data('id');

    $.get("includes/ajax/GetBanDetails.php", {id: id}, function (data) {
      $.each(JSON.parse(data), function (index, value) {
        if(~index.indexOf("_link"))
          $("#" + index).find("a").attr("href", value);
        else {
          if(jQuery.isPlainObject(value))
            $("#" + index).text(value[0]);
          else
            $("#" + index).text(value);
        }
      });
      $('#banlistModal').modal('toggle');
      $("tr").css("cursor", "pointer");
    });
  });

  $(".unban").on('click', function (event) {
    event.stopPropagation();
    var name = $(this).parent().parent().children("td").eq(1).text();

    if(confirm("Unban: \""+ name +"\" ?")) {
      var id = $(this).data('id');
      $.post("includes/ajax/PlayerActions.php", {id: id, action: 'unban'}, function (data) {
        alert(data);
        location.reload();
      });
    }
  });

  $(".delete").on('click', function (event) {
    event.stopPropagation();
    var name = $(this).parent().parent().children("td").eq(1).text();

    if(confirm("Delete: \""+ name +"\" ?")) {
      var id = $(this).data('id');
      $.post("includes/ajax/PlayerActions.php", {id: id, action: 'delete'}, function (data) {
        if(data !== "1")
          alert("Error while processing !");

        location.reload();
      });
    }
  });

  $(".serverInfoModal").on('click', function (event) {
    event.preventDefault();
    var id = $(this).find('.server_id').data('id');
    $.get("includes/ajax/GetServerDetails.php", {id: id}, function (data) {
      $("#appendServerPlayersInfo").html(data);

      $(".ban").on('click', function (event) {
        event.stopPropagation();
        var name = $(this).data('id');
        var serverid = $("#serverID").text();

        $("#staticName").val(name);
        $("#serverModal").modal("hide");
        $("#addBanModal").modal("show");

        $("#confirmBan").on('click', function () {
          var time = $("#inputTime").val();
          var reason = $("#inputReason").val();

          $.post("includes/ajax/PlayerActions.php", {id: name, action: 'ban', serverid: serverid, time: time, reason: reason}, function (data) {
            alert(data);
          });
          location.reload();
        });
      });

      $(".kick").on('click', function (event) {
        event.stopPropagation();
        var name = $(this).data('id');
        var serverid = $("#serverID").text();

        if(confirm("Kick: \""+ name +"\" ?")) {
          $.post("includes/ajax/PlayerActions.php", {id: name, action: 'kick', serverid: serverid}, function (data) {
            alert(data);
          });
          location.reload();
        }
      });
    });

    $("#serverID").text($(this).children("td").eq(0).text());
    $("#serverName").text($(this).children("td").eq(4).text());
    $("#serverPlayers").text($(this).children("td").eq(5).text());
    $("#serverMap").text($(this).children("td").eq(6).text());

    $('#serverModal').modal('toggle');
  });

  $(".addServer").on('click', function (event) {
    $("#addServerModal").modal("show");
  });

  $(".addOfflineBan").on('click', function (event) {
    $("#addOfflineBanModal").modal("show");
  });

  $("#confirmServer").on('click', function (event) {
    event.preventDefault();

    if($("#inputIp").val() === "") {
      alert("Empty IP");
      return;
    }

    $.post("includes/ajax/ServerActions.php", {action: 'add', ip: $("#inputIp").val(), port: $("#inputPort").val(), rcon: $("#inputRcon").val()}, function (data) {
      $("#addServerModal").modal("hide");

      if(data !== "1")
        alert("Error while processing !");

      location.reload();
    });
  });

  $("#confirmOfflineBan").on('click', function (event) {
    event.preventDefault();

    if($("#inputSid").val() === "") {
      alert("Empty SID");
      return;
    }

    if($("#inputTime").val() === "") {
      alert("Empty SID");
      return;
    }

    if($("#inputReason").val() === "") {
      alert("Empty SID");
      return;
    }

    $.post("includes/ajax/PlayerActions.php", {id: $("#inputSid").val(), action: 'offline_ban', time: $("#inputTime").val(), reason: $("#inputReason").val()}, function (data) {
      $("#addOfflineBanModal").modal("hide");
      alert(data);
      location.reload();
    });
  });

  $(".deleteServer").on('click', function (event) {
    event.stopPropagation();

    var id = $(this).data("id");
    $.post("includes/ajax/ServerActions.php", {action: 'delete', id: id}, function (data) {
      if(data !== "1")
        alert("Error while processing !");

      location.reload();
    });
  });

})(jQuery); // End of use strict