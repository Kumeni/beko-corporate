const flyInFromRight = (elements, index) => {
    if(index < elements.length){
        elements[index].style.left = "0px";
        elements[index].style.opacity = "1";
        setTimeout(()=>{flyInFromRight(elements, (index+1))}, 100);
    }
}

const flyOutFromLeft = (elements, index) => {
    console.log(elements[3]);
    if(index < elements.length){
        if(index >= 0){
            console.log(elements[index]);
            elements[index].style.left = "-60px";
            elements[index].style.opacity = "0";
        }
        flyInFromRight(elements, (index+1));
    }
}

const prepareFlyInFromRight = elements => {
    let i = 0;
    for(i=0; i<elements.length; i++){
        elements[i].style.left = "60px";
        elements[i].style.opacity = "0";
    }
}

let nextSlide = 1;
swiperLanding.on('slideChange', function (event) {
    let slides = document.getElementsByClassName("landing"), i, j, elementsToAnimate;
    console.log(event.realIndex);

    for(i=0; i<slides.length; i++){
        elementsToAnimate  = slides[i].getElementsByClassName("fly-in-from-right");

        if(event.realIndex == i){
            setTimeout(()=>{
                flyInFromRight(elementsToAnimate, 0);
                setTimeout(()=>{
                    flyOutFromLeft(elementsToAnimate, -1);
                }, 4000);
            }, 6000);
            //break;
        } else {
            /**
             * Prepare fly in from right
             */
            prepareFlyInFromRight(elementsToAnimate);
        }
    }
});

const nextLandingSlide = () => {
    if(swiperLanding) swiperLanding.slideNext();
    let slides = document.getElementsByClassName("landing"), i, j, elementsToAnimate;
    
    for(i=0; i<slides.length; i++){
        elementsToAnimate  = slides[i].getElementsByClassName("fly-in-from-right");

        if(event.realIndex == i){
            setTimeout(()=>{
                flyInFromRight(elementsToAnimate, 0);
                setTimeout(()=>{
                    flyOutFromLeft(elementsToAnimate, -1);
                }, 4000);
            }, 6000);
            //break;
        } else {
            /**
             * Prepare fly in from right
             */
            prepareFlyInFromRight(elementsToAnimate);
        }
    }
}

const prevLandingSlide = () => {
    if(swiperLanding) swiperLanding.slidePrev();
    let slides = document.getElementsByClassName("landing"), i, j, elementsToAnimate;
    
    for(i=0; i<slides.length; i++){
        elementsToAnimate  = slides[i].getElementsByClassName("fly-in-from-right");

        if(event.realIndex == i){
            setTimeout(()=>{
                flyInFromRight(elementsToAnimate, 0);
                setTimeout(()=>{
                    flyOutFromLeft(elementsToAnimate, -1);
                }, 4000);
            }, 6000);
            //break;
        } else {
            /**
             * Prepare fly in from right
             */
            prepareFlyInFromRight(elementsToAnimate);
        }
    }
}