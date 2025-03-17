let categoriesSwiper = new Swiper('.swiper-categories', {
  // Optional parameters
  speed:1000,
  spaceBetween:0,
  slidesPerView:1,
  direction: 'horizontal',
  loop: false,
  //effect:'coverflow',
  //effect:'cards',
  
  // If we need pagination
  allowTouchMove:true,
  pagination: {
    el: '.swiper-pagination',
    clickable:true,
  },

  /*autoplay:{
    delay:4000,
    disableOnInteraction:false,
  },*/

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