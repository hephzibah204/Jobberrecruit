$(document).ready(function () {
  $(window).on("scroll", function () {
    if ($(this).scrollTop() > 50) {
      $(".sticky-navbar").addClass("is-sticky");
    } else {
      $(".sticky-navbar").removeClass("is-sticky");
    }
  });

  $(".vacancy-item").on("click", function (e) {
    // Allow default link behavior, but add visual feedback if needed
    $(this)
      .addClass("active")
      .delay(200)
      .queue(function () {
        $(this).removeClass("active").dequeue();
      });
  });

  $(".category-card").on("click", function (e) {
    // Visual feedback on click
    $(this)
      .addClass("active")
      .delay(200)
      .queue(function () {
        $(this).removeClass("active").dequeue();
      });
  });

  $(".category-card").hover(
    function () {
      $(this).addClass("hovered");
    },
    function () {
      $(this).removeClass("hovered");
    }
  );

  $(".job-card i.bi-bookmark").click(function () {
    $(this).toggleClass("bi-bookmark bi-bookmark-fill text-primary");
  });

  $(".testimonial-carousel").owlCarousel({
    loop: true,
    margin: 20,
    nav: true,
    dots: true,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    responsive: {
      0: { items: 1 },
      768: { items: 2 },
      992: { items: 3 },
    },
    navText: [
      "<i class='bi bi-arrow-left'></i>",
      "<i class='bi bi-arrow-right'></i>",
    ],
  });

  $(".partners-carousel").owlCarousel({
    loop: true,
    margin: 30,
    autoplay: true,
    autoplayTimeout: 2000,
    autoplayHoverPause: true,
    dots: false,
    nav: false,
    responsive: {
      0: { items: 2 },
      576: { items: 3 },
      768: { items: 4 },
      992: { items: 5 },
      1200: { items: 6 },
    },
  });
});
