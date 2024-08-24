let swiperLanding = new Swiper('.homepage-landing', {
  // Optional parameters
  speed:6000,
  spaceBetween:0,
  slidesPerView:1,
  direction: 'horizontal',
  loop: true,
  effect:'coverflow',
  //effect:'cards',
  
  // If we need pagination
  allowTouchMove:true,
  pagination: {
    el: '.swiper-pagination',
    clickable:true,
  },

  autoplay:{
    delay:4000,
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
});