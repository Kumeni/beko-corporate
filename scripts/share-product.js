const shareProduct = async (productIndex, event) => {
    event.preventDefault();

    let activeProduct = products[productIndex];
    console.log("Sharing product");
    let url = window.location.origin + "/products/product.php?product-id="+activeProduct.id;

    if(navigator.share){
        try{
            await navigator.share({
                title:activeProduct.name,
                text: activeProduct.description,
                url: url,
            });
            //console.log('Content shared successfully');
        } catch(error) {
            console.error("Error sharing content:", error);
        }
    } else {
        alert("Web Share API not supported in the browser");
    }
}

const shareRelatedProduct = async (productIndex, event) => {
    event.preventDefault();

    let activeProduct = relatedProducts[productIndex];
    console.log("Sharing product");
    let url = window.location.origin + "/products/product.php?product-id="+activeProduct.id;

    if(navigator.share){
        try{
            await navigator.share({
                title:activeProduct.name,
                text: activeProduct.description,
                url: url,
            });
            //console.log('Content shared successfully');
        } catch(error) {
            console.error("Error sharing content:", error);
        }
    } else {
        alert("Web Share API not supported in the browser");
    }
}

const shareSingleProduct = async(event) => {
    event.preventDefault();

    let url = window.location.origin + "/products/product.php?product-id="+product.id;

    if(navigator.share){
        try{
            await navigator.share({
                title:product.name,
                text: product.description,
                url: url,
            });
            //console.log('Content shared successfully');
        } catch(error) {
            console.error("Error sharing content:", error);
        }
    } else {
        alert("Web Share API not supported in the browser");
    }
}