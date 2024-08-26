let flyInFromRightCallbackTimeout, slidesTransitionTimeout, slidesDelayTimeout;
const flyInFromRight = (elements, index) => {
    if(index < elements.length){
        elements[index].style.left = "0px";
        elements[index].style.opacity = "1";
        if(index == 0)elements[0].style.boxShadow = "15vw 1px 60px #27327391";
        flyInFromRightCallbackTimeout = setTimeout(()=>{flyInFromRight(elements, (index+1))}, 100);
    }
}

const flyOutFromLeft = (elements, index) => {
    if(index < elements.length){
        if(index >= 0){
            console.log(elements[index]);
            elements[index].style.left = "-60px";
            elements[index].style.opacity = "0";
            if(index == 0)elements[0].style.boxShadow = "unset";
        }
        flyInFromRight(elements, (index+1));
    }
}

const prepareFlyInFromRight = elements => {
    let i = 0;
    for(i=0; i<elements.length; i++){
        elements[i].style.opacity = "0";
        elements[i].style.left = "60px";
    }
}

let nextSlide = 1, previousIndex;
swiperLanding.on('slideChange', function (event) {
    let slides = document.getElementsByClassName("landing"), i, j, elementsToAnimate;
    console.log(event.realIndex);

    for(i=0; i<slides.length; i++){
        elementsToAnimate  = slides[i].getElementsByClassName("fly-in-from-right");

        if(event.realIndex == i){
            slidesTransitionTimeout = setTimeout(()=>{
                flyInFromRight(elementsToAnimate, 0);
                slidesDelayTimeout = setTimeout(()=>{
                    flyOutFromLeft(elementsToAnimate, -1);
                }, 4000);
            }, 6000);
            nextSlide = event.realIndex + 1;
            if(nextSlide >= slides.length) nextSlide = 0;

            if(event.realIndex == (slides.length -1)){
                /**
                 * If end of slides prepare the first slide
                 */
                elementsToAnimate = slides[0].getElementsByClassName("fly-in-from-right");
                prepareFlyInFromRight(elementsToAnimate);
            }
        }{
            /**
             * If the slide comes after active slide prepare fly in from right
             */
            elementsToAnimate = slides[i].getElementsByClassName("fly-in-from-right");
            if(i > event.realIndex){
                prepareFlyInFromRight(elementsToAnimate);
            } else if(i < event.realIndex){
                flyInFromRight(elementsToAnimate, 0);
            }
        }
    }
});

const nextLandingSlide = () => {
    clearTimeout(flyInFromRightCallbackTimeout);
    clearTimeout(slidesTransitionTimeout);
    clearTimeout(slidesDelayTimeout);
    if(swiperLanding) swiperLanding.slideNext();
}

const prevLandingSlide = () => {
    clearTimeout(flyInFromRightCallbackTimeout);
    clearTimeout(slidesTransitionTimeout);
    clearTimeout(slidesDelayTimeout);
    if(swiperLanding) swiperLanding.slidePrev();
}