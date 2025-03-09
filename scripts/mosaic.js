let initialMosaicHeight;
function mosaic(boxes, separation=30){
    let availableColumns = [];

    //This block creates the number of columns
    let i, j, end=false, k;
    for (j = 0; j < boxes.length; j++){
        if ( availableColumns.length ==0 ){
            availableColumns.push({
                left:boxes[j].getBoundingClientRect().x
            })
            continue;
        }

        //The code to know the column already exists
        for (i=0; i<availableColumns.length; i++){
            if(availableColumns[i].left == boxes[j].getBoundingClientRect().x){
                end=true;
                break;
            }
        }
        
        if(end)
            break;
        availableColumns.push({
            left:boxes[j].getBoundingClientRect().x
        })
    }
    
    console.log(availableColumns);

    let m, rowTop = rowBottom = document.getElementsByClassName("mosaic")[0].offsetTop, nextLongestImage, lowestBottom = 0, containerHeight = 0;
    for (m=0; m < boxes.length; m++){

        //obtain the maximum height of the row
        let max;
        if(m%availableColumns.length === 0){
            //if its the first element in the row, find the bottom of the row
            max=0;
            for(i=m; (i<m+availableColumns.length && i<boxes.length); i++){
                //loop through all the elements in the row to find the bottom 
                if(boxes[i].getBoundingClientRect().height > max){
                    max=boxes[i].getBoundingClientRect().height;
                    nextLongestImage = i;
                }
            }
            rowBottom = rowBottom+max;
        }
        //use it as the element.offsetTop
        //update after every 4 loops
        
        /**
         * To find all the initial heights and separations
         */
        let n = m - availableColumns.length, pixelsFromTop = 0;
        while(n >= 0){
            pixelsFromTop = pixelsFromTop + separation + boxes[n].getBoundingClientRect().height;
            n = n - availableColumns.length;
        }

        let top = document.getElementsByClassName("mosaic")[0].offsetTop + pixelsFromTop;
        
        //console.log(top - rowTop);
        boxes[m].style.top = (top - rowTop) + "px";

        if(m%availableColumns.length == (availableColumns.length -1))
            rowTop = rowBottom;

        /**
         * This code gets the largest bottom and updates the height of the mosaic
         * Get the bottoms of the last row and use them to set the height;
         */
        if(pixelsFromTop + boxes[m].getBoundingClientRect().height > containerHeight){
            containerHeight = pixelsFromTop + boxes[m].getBoundingClientRect().height;
        };
        if(m== boxes.length -1) document.getElementsByClassName("mosaic")[0].style.height = containerHeight+100+"px";
    }
}

let boxes = document.getElementsByClassName("box");
console.log(boxes);
setInterval(()=>{
    mosaic(document.getElementsByClassName("box"), 10);
}, 2000)