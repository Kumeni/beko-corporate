/**
 * On hover, show the navigation menu,
 */

let navigationState = false;
const toggleNavigation = () => {
    if( navigationState == false){
        /**
         * Show the navigation
         */
        //document.getElementsByClassName("hamburger-menu")[0].style.display = "block";
        //document.getElementsByClassName("hamburger-menu")[0].getElementsByTagName("ul")[0].style.width = "100vw";
        //document.getElementsByClassName("hamburger-menu")[0].getElementsByTagName("ul")[0].style.whiteSpace = "unset";
        document.getElementById("hamburger-menu-container").style.width = "100%";
        navigationState = true;

    } else {
        /**
         * Hide the navigation
         */
        //document.getElementsByClassName("hamburger-menu")[0].style.display = "none";
        //document.getElementsByClassName("hamburger-menu")[0].getElementsByTagName("ul")[0].style.width = "0vw";
        //document.getElementsByClassName("hamburger-menu")[0].getElementsByTagName("ul")[0].style.whiteSpace = "nowrap";
        document.getElementById("hamburger-menu-container").style.width = "0%";
        navigationState = false;
    }
}

//let landing = document.getElementsByClassName("landing");
//console.log(landing[0]);

/*function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    console.log(rect);
    console.log(rect.top);
    return (
        //rect.top >= -window.innerHeight &&
        rect.top >= -rect.height-1
        //rect.left >= 0 &&
        //rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
        //rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
    return (
        //rect.top >= -window.innerHeight &&
        rect.top <= -rect.height &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}*/

// Usage example
/*document.addEventListener('scroll', function() {
    const element = document.getElementsByClassName("landing")[0];

    console.log(element.getBoundingClientRect().top);
    if (isElementInViewport(element) || element.getBoundingClientRect().top == 0) {
        //console.log('Element is in the viewport');
        document.getElementsByClassName("navigation")[0].style.backgroundColor = "#fcc12c00";
    } else {
        //console.log('Element is not in the viewport');
        document.getElementsByClassName("navigation")[0].style.backgroundColor = "#FCC22C";
    }
});*/