const swiper = new Swiper('.swiper', {
    // Optional parameters
    speed:300,
    spaceBetween:30,
    slidesPerView:1,
    direction: 'horizontal',
    loop: true,
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable:true,
    },

    autoplay:{
      delay:2500,
      disableOnInteraction:false,
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  
    // And if we need scrollbar
    scrollbar: {
      el: '.swiper-scrollbar',
    },

    // Responsive breakpoints
    /*breakpoints: {
        //when window width is <=425px
        425:{
          slidesPerView:1,
        },
        // when window width is >= 580px
        580: {
            slidesPerView: 2,
            //spaceBetween: 20
        },
        // when window width is >= 1088px
        1088: {
            slidesPerView: 3,
            //spaceBetween: 30
        },
    }*/
});