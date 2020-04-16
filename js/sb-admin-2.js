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

})(jQuery); // End of use strict