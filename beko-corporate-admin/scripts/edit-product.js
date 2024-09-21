let vehicleImages = [], deletedImages = [], numberPlate, description, selectedTracker,  activeProduct, vehicles = [],
    storageWidth, storageHeight, storageLength, maximumWeight, ratePerKm;

let productCategories;
let productName, productDescription;
let varieties = [{
    images:[],
    name:undefined,
    description:undefined,
    price:undefined
}];
let deletedVarieties = [];
let products;
let specifications = [{
    groupName:"Dimensions and Weight",
    propertiesAndValues:[
        {
            property:"Length",
            value:undefined,
        },
        {
            property:"Width",
            value:undefined,
        },
        {
            property:"Height",
            value:undefined,
        }
    ]
}];
let deletedSpecifications = [];
let deletedPropertyAndValues = [];
let selectedCategories = [];
let additionalInfos = [{}];
let activeCategories;

const editProduct = productIndex => {
    //First show the popup
    //Just that;
    let product;
    if(productIndex != undefined){
        if(productIndex == -1){
            product = {};
        } else {
            product = products[productIndex];
        }
        
    }
    
    let popup = document.getElementsByClassName("popup")[0];

    if(product == undefined){
        //Close the popup
        popup.style.visibility = "hidden";
        popup.style.zIndex = "-10";

        document.getElementsByClassName("product-form")[0].reset();
        deletedImages = [];

        productName= undefined;
        productDescription = undefined;
        document.getElementById("product-name-input").value = null;
        document.getElementById("product-description").innerHTML = null;
        varieties = [{
            images:[],
            name:undefined,
            description:undefined,
            price:undefined
        }];
        updateVarieties(varieties);

        specifications = [{
            groupName:"Dimensions and Weight",
            propertiesAndValues:[
                {
                    property:"Length",
                    value:undefined,
                },
                {
                    property:"Width",
                    value:undefined,
                },
                {
                    property:"Height",
                    value:undefined,
                }
            ]
        }];
        updateSpecifications(specifications);

        additionalInfos = [{}];
        updateAdditionalInfos(additionalInfos);

        selectedCategories = [];
        activeCategories = generate2DArrayFromTreeDataStructure(productCategories);
        updateProductCategories(activeCategories);
        //getVehicleCategories();
        activeProduct = undefined;
    } else {
        //If it editing the
        
        if(product.id){
            /**
             * Autofill product name
             */
            document.getElementById("product-name-input").value = product.name;
            handleProductNameChange({target:{value:product.name}});

            /**
             * Autofill product description
             */
            document.getElementById("product-description").innerHTML = product.description;
            handleProductDescriptionChange({target:{value:product.description}});

            /**
             * Autofill varieties
             */
            varieties = product.varieties;
            updateVarieties(varieties);

            /**
             * Autofill specifications
             */
            //console.log(product.specifications);
            specifications = product.specifications;
            updateSpecifications(specifications);

            selectedCategories = product.categories;
            activeCategories = generate2DArrayFromTreeDataStructure(productCategories);
            updateProductCategories(activeCategories);
            console.log(selectedCategories);

            additionalInfos = product.articles;
            if(additionalInfos.length == 0){
                additionalInfos.push({});
            }
            console.log(additionalInfos);
            updateAdditionalInfos(additionalInfos);

            activeProduct = product;

        } else {
            //If creating a vehicle

        }
        
        popup.style.visibility = "visible";
        popup.style.zIndex = "10";
    }
}

const handleVarietyImageChange = (index, event) => {
    let imagePreview = document.getElementsByClassName("image-preview")[index];
    let imageInput = document.getElementsByClassName("image-input")[index];
    document.getElementsByClassName("image-input-error")[index].innerHTML ="";
    //let removeImageButton = document.getElementById("remove-image-button");

    if(event.target.files[0] == undefined){
        return;
    }

    //console.log(imagePreview);
    varieties[index].images = varieties[index].images.concat(event.target.files[0]);
    //vehicleImage = ;
    
    let reader, innerHTML = ``;

    if(varieties[index].images.length == 0){
        imagePreview.innerHTML = `<div class="no-image"><p>No Image</p></div>`;
    } else varieties[index].images.forEach((varietyImage, index2) => {
        if(varietyImage.id == undefined){
            reader = new FileReader();
            reader.readAsDataURL(varietyImage);
            reader.onload = event => {
                //imagePreview.style.display = "inline";
                //removeImageButton.style.display = "block";

                //imagePreview.src = ;
                innerHTML += `
                    <div class="single-image-preview">
                        <button title="Remove image" onclick="removeImage(${index}, ${index2}, event)" class="remove-button">&times;</button>
                        <img class="variety-image" src="${event.target.result}" />
                    </div>`;

                    if(index2 == varieties[index].images.length-1){
                        imagePreview.innerHTML = innerHTML;
                        imageInput.value = "";

                        /**Scroll to the end of the image preview */
                        setTimeout(() => {imagePreview.scrollTo(10000, 0)}, 500);
                        ;
                    }
            }
        } else {
            innerHTML += `
                <div class="single-image-preview">
                    <button onclick="removeImage(event, ${index})" class="remove-button">&times;</button>
                    <img class="variety-image" src=".${varietyImage.path}" />
                </div>`;

                if(index2 == varieties[index].images.length-1){
                    imagePreview.innerHTML = innerHTML;
                    imageInput.value = "";

                    /**Scroll to the end of the image preview */
                    setTimeout(() => {imagePreview.scrollTo(10000, 0)}, 500);
                    ;
                }
        }
    });

    if(vehicleImages.length >= 8) imageInput.style.display = "none";
}

const removeImage = (event, index) => {
    event.preventDefault();
    //let imagePreview = document.getElementById("image-preview");
    let imageInput = document.getElementById("image-input");
    let imagePreview = document.getElementsByClassName("image-preview")[0];
    //let removeImageButton = document.getElementById("remove-image-button");

    console.log(index);
    console.log(vehicleImages);
    if(vehicleImages[index].id){
        deletedImages = deletedImages.concat(vehicleImages[index].id);
    }

    vehicleImages.splice(index, 1);

    let reader, innerHTML = ``;

    if(vehicleImages.length == 0){
        imagePreview.innerHTML = ``;
    } else vehicleImages.forEach((vehicleImage, index) => {
        
        if(vehicleImage.id == undefined){
            reader = new FileReader();
            reader.readAsDataURL(vehicleImage);
            reader.onload = event => {
                //imagePreview.style.display = "inline";
                //removeImageButton.style.display = "block";
                imagePreview.src = event.target.result;
                innerHTML += `
                    <div>
                        <button onclick="removeImage(event, ${index})" class="remove-button">&times;</button>
                        <img class="image" src="${imagePreview.src}" />
                    </div>`;

                if(index == vehicleImages.length -1){
                    imagePreview.innerHTML = innerHTML;
                    imageInput.value = "";

                    /**Scroll to the end of the image preview */
                    imagePreview.scrollTo(10000, 0);
                }
            }
        } else {
            //deletedImages = deletedImages.concat(vehicleImage.id);
            innerHTML += `
                    <div>
                        <button onclick="removeImage(event, ${index})" class="remove-button">&times;</button>
                        <img class="image" src=".${vehicleImage.path}" />
                    </div>`;

                if(index == vehicleImages.length -1){
                    imagePreview.innerHTML = innerHTML;
                    imageInput.value = "";

                    /**Scroll to the end of the image preview */
                    imagePreview.scrollTo(10000, 0);
                }
        }
    });

    imageInput.style.display = "block";
    imageInput.value = "";
}

const getProductCategories = () => {
    const API_ENDPOINT = "./server/product-categories.php";
    const request = new XMLHttpRequest();

    request.open("GET", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            //console.log(request.response);
            //console.log(JSON.parse(request.response));
            //console.log(request.response);
            productCategories = JSON.parse(request.response);
            //console.log(productCategories);
            activeCategories = generate2DArrayFromTreeDataStructure(productCategories);
            updateProductCategories(activeCategories);
            
            //console.log(activeCategories);
            //console.log(vehicleCategories);
            //updateFormVehicleCategories(vehicleCategories);
            //categorizedSensors = categorizeSensors(sensors);
            //updateFormSensors(categorizedSensors, activeTracker);
        }
    }

    request.send();
}
getProductCategories();

const updateProductCategories = (productCategories) => {
    /**
     * productCategories is a 2D array of the categories
     */
    let slides = document.getElementsByClassName("categories")[0].getElementsByClassName("swiper-slide");
    let noOfSlides = slides.length, checked;
    
    let innerHTML = ``, i;
    productCategories.shift();
    /**
     * If productCategories is less that no of slides remove the extra slides
     */
    if(productCategories.length < noOfSlides){
        for(i=0; i < noOfSlides; i++){
            if(i >= productCategories.length - 1){
                categoriesSwiper.removeSlide(i);
            }
        }
    }

    
    //console.log(productCategories);
    for(i=0; i<productCategories.length; i++){
        if(i < noOfSlides){
            /**
             * Insert the html to the slides
             */
            innerHTML = ``;
            
            productCategories[i].forEach((element, index) => {
                checked = false;
                selectedCategories.forEach((element2, index) => {
                    if(element2.id == element.id){
                        checked=true;
                    }
                });
                innerHTML += `<div>${element.name} <input ${ checked && "checked"} type="checkbox" onchange='handleCategoryChange(${JSON.stringify(element)}, event)' /></div>`;
            });
            
            slides[i].getElementsByClassName("same-parent-categories")[0].innerHTML = innerHTML;
        } else {
            /**
             * Create new slides;
             */
            innerHTML = `<div class="swiper-slide">
                            <div class="same-parent-categories">`;
                                productCategories[i].forEach((element, index) => {
                                    checked = false;
                                    selectedCategories.forEach((element2, index) => {
                                        if(element2.id == element.id){
                                            checked=true;
                                        }
                                    });
                                    innerHTML += `<div>${element.name} <input ${checked && "checked"} type="checkbox" onchange='handleCategoryChange(${JSON.stringify(element)}, event)'/></div>`;
                                });
            innerHTML += `</div>
                        </div>`;

            categoriesSwiper.appendSlide(innerHTML);
        }
    }
}


const handleCategoryChange = (category, event) => {
    let i, selected = false, indexToRemove = -1;
    if(event.target.checked == true){
        /**
         * Push the category into the
         */
        selectedCategories.forEach((element, index) => {
            if(element.id == category.id){
                selected = true;
            }
        });
        if(!selected){
            selectedCategories.push(category);
        }
    } else {
        /**
         * Remove the category from the categories array;
         */
        selectedCategories.forEach((element, index) => {
            if(element.id == category.id){
                indexToRemove = index
            }
        });

        if(indexToRemove != -1){
            selectedCategories.splice(indexToRemove, 1);
        }
    }

    //activeCategories = getActiveFlattenedCategories(category);
    //updateProductCategories(activeCategories);
}

/**
 * Generate 2D array from a node in a tree data structure;
 */
const generate2DArrayFromTreeDataStructure = (element, level=0, generatedCategories = []) => {
    if(generatedCategories[level] == undefined){
        generatedCategories[level] = [];
    }

    generatedCategories[level].push({
        id:element.id,
        name:element.name,
        parent:element.parent
    });

    if(element.categories && element.categories.length > 0){
        element.categories.forEach((element, index) => {
            generate2DArrayFromTreeDataStructure(element, (level+1), generatedCategories);
        });
    }

    return generatedCategories;
}

const getCategoryFromTree = (category, tree) => {

    let treeCategory;

    const getAnElementFromTreeDataStructure = (target, tree) =>{
        if(tree.id == target.id) treeCategory = tree;
        
        if(tree.categories)
            tree.categories.forEach((category, index) => {
                getAnElementFromTreeDataStructure(target, category);
            });
    }
    getAnElementFromTreeDataStructure(category, tree);

    return treeCategory;
}

const getActiveFlattenedCategories = (activeCategory) => {
    let categoryLevel, i;

    let treeCategory = getCategoryFromTree(activeCategory, productCategories);
    //let flattenedCategories = generate2DArrayFromTreeDataStructure(categories);
    let flattenedCategories = activeCategories;
    let flattenedTreeCategories = generate2DArrayFromTreeDataStructure(treeCategory);
    let newFlattenedCategories = [];

    for(i=0; i<flattenedCategories.length; i++){
        for(j=0; j<flattenedCategories[i].length; j++){
            if(flattenedCategories[i][j].id == activeCategory.id){
                categoryLevel = i;
            }
        }
    }
    
    if(categoryLevel === undefined) return;

    for(i=0; i<flattenedCategories.length; i++){
        if(i<=categoryLevel){
            newFlattenedCategories[i] = flattenedCategories[i];
            //newFlattenedCategories.push();
        }
    }

    for(i=0; i<flattenedTreeCategories.length; i++){
        if(i==0) continue;
        newFlattenedCategories.push(flattenedTreeCategories[i]);
    }

    flattenedCategories = newFlattenedCategories;
    return flattenedCategories;
}

/**
 * General function that generates the full 2D array
 * Another function that generates a specific 2D array
 */
const handleProductNameChange = event => {
    productName = event.target.value;
    document.getElementById("product-name-error").innerHTML = "";
}

const handleProductDescriptionChange = event => {
    productDescription = event.target.value;
    document.getElementById("product-description-error").innerHTML = "";
    /**Set error to null */
}

const handleVarietyNameChange = (index, event) => {
    varieties[index].name = event.target.value;
    document.getElementsByClassName("variety-name-error")[index].innerHTML = "";
}

const handleVarietyPriceChange = (index, event) => {
    varieties[index].price = event.target.value;
    document.getElementsByClassName("variety-price-error")[index].innerHTML = "";
}

const handleVarietyDescriptionChange = (index, event) => {
    varieties[index].description = event.target.value;
    document.getElementsByClassName("variety-description-error")[index].innerHTML = "";
}

const handleGroupNameChange = (index, event) => {
    specifications[index].groupName = event.target.value;
}

const handlePropertyNameChange = (propetyIndex, specificationIndex, event) => {
    specifications[specificationIndex].propertiesAndValues[propetyIndex].property = event.target.value;
    console.log(specifications);
}

const handleValueChange = (valueIndex, specificationIndex, event) => {
    specifications[specificationIndex].propertiesAndValues[valueIndex].value = event.target.value;
    console.log(specifications);
}

const handleProductUpload = event => {
    event.preventDefault();
    /**
     * Organizing data for post request
     */

    let formData = new FormData(), canSubmit = true;

    /**
     * Deleted specification
     */
    if(deletedPropertyAndValues.length > 0){
        formData.append("deleted-property-and-values", JSON.stringify(deletedPropertyAndValues));
    }
    
    /**
     * Deleted varieties
     */
    if(deletedVarieties.length > 0){
        formData.append("deleted-varieties", JSON.stringify(deletedVarieties));
    }
    /**
     * Processing product name
     */
    if(productName){
        formData.append("product-name", productName);
    } else {
        /**
         * Handle Product Name Missing Error
         */
        canSubmit = false;
        document.getElementById("product-name-error").innerHTML = "Product Name is required!";
    }

    /**
     * Processing product description
     */
    if(productDescription){
        formData.append("product-description", productDescription);
    } else {
        /**
         * Handle Product Description Missing Error
         */
        canSubmit = false;
        document.getElementById("product-description-error").innerHTML = "Product Description is required!";
    }

    /**
     * Process Varieties Object
     */
    let varietyImages = [], newVarieties = [], holder;
    varieties.map((element, index) => {
        /**
         * Create a 2 dimensional array of images
         */
        if(element.images && element.images.length == 0){
            /**
             * If Element Has no images, throw an error
             */
            canSubmit = false;
            document.getElementsByClassName("image-input-error")[index].innerHTML = "Variety Image(s) are required!";
        }
        varietyImages[index] = element.images;
        //delete element.images;
        //holder = {};
        /**
         * Check if variety name is present
         */
        if(element.name == undefined || element.name == "" || element.name == null){
            canSubmit = false;
            document.getElementsByClassName("variety-name-error")[index].innerHTML = "Variety Name is required!";
        }
        //holder.name = element.name;

        /**
         * Check if variety price is present;
         */
        if(element.price == undefined || element.price == "" || element.price == null){
            canSubmit = false;
            document.getElementsByClassName("variety-price-error")[index].innerHTML = "Variety Price is required!";
        }
        //holder.price = element.price;

        /**
         * Check if variety description is present
         */
        if(element.description == undefined || element.description == "" || element.description == null){
            canSubmit = false;
            document.getElementsByClassName("variety-description-error")[index].innerHTML = "Variety Description is required!";
        }
        //holder.description = element.description;

        //newVarieties.push(holder);
    });

    varieties.map((element, index) => {
        delete element.images;
    });

    formData.append("varieties", JSON.stringify(varieties));
    //formData.append("varieties", JSON.stringify(newVarieties));
    
    /**
     * Process specifications;
     * Rules;   Group name is optional
     *          Property is required
     *          Value is required
     *          We'll process it on the server
     */
    console.log(specifications);
    formData.append("specifications", JSON.stringify(specifications));
    
    /**
     * Process product categories
     */
    formData.append("categories", JSON.stringify(selectedCategories));
    
    /**
     * Process Additional Infos
     */
    let i, articles = [], articlesDeltas = [], deletedArticles = [];
    for(i = 0; i < quill.length; i++){
        holder = additionalInfos[i];
        
        holder.delta = quill[i].getContents();
        holder.text = quill[i].getText();
        holder.html = quill[i].getSemanticHTML();

        if(holder.text.length == 1 || holder.text == "" || holder.text == " "){
            if(holder.id != undefined) deletedArticles.push(holder.id);
            continue;
        } else {
            articles.push(holder);
        }
        
        articlesDeltas.push(quill[i].getContents());
    }
    console.log(articles);
    formData.append("articles", JSON.stringify(articles));
    console.log(JSON.stringify(articles));
    if(deletedArticles.length > 0) formData.append("deleted-articles", JSON.stringify(deletedArticles));
    
    articlesDeltas.forEach((element, index) => {
        formData.append("articles-deltas[]", JSON.stringify(element));
    })
    
    

    /**
     * Process images
     * varietyImages variable is a 2 dimensional array of varietyImages to be flattened;
     */
    // Flatten the 2D array and append images to formData
    varietyImages.forEach((row, i) => {
        row.forEach((image, j) => {
            if(image.id == undefined){
                formData.append(`image_${i}_${j}`, image); // Append each image with a unique key
            }
        });
    });

    /**
     * If updating an existing existing product, add the product id
     */
    if(activeProduct != undefined){
        formData.append(`id`, activeProduct.id);
    }
    //Only signedin users can upload vehicles
    //if(credentials == null || credentials == undefined) {return;}

    
    //formData.append("deleted-images", JSON.stringify(deletedImages));

    //console.log(deletedImages);

    //formData.append("credentials", credentials);*/

    if(canSubmit == false) return;

    console.log("request wase sent!");
    const API_ENDPOINT = "./server/products.php";
    const request = new XMLHttpRequest();

    request.open("POST", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            console.log(request.response);
            products = JSON.parse(request.response);
            products = JSON.parse(products);
            console.log(products);
            //products = JSON.parse(products);
            console.log(products);
            updateProducts(products);
            editProduct();
        }
    }

    request.send(formData);
}

const deleteProduct = vehicleId => {

    let formData = new FormData();
    formData.append("id", vehicleId);
    formData.append("deleted", 1);

    const API_ENDPOINT = "./server/transport-vehicles.php";
    const request = new XMLHttpRequest();

    request.open("POST", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            vehicles = JSON.parse(request.response);
            updateVehicles(vehicles);
            editVehicle();
        }
    }

    request.send(formData);
}

const getProducts = event => {
    const API_ENDPOINT = "./server/products.php";
    const request = new XMLHttpRequest();

    request.open("GET", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            console.log(request.response);
            products = JSON.parse(request.response);
            products = JSON.parse(products);
            console.log(products);
            updateProducts(products);
            editProduct();
        }
    }

    request.send();
}
getProducts();

const updateProducts = products => {
    let innerHTML= ``;
    products.forEach((product, index) => {

        let url = ``, alt = ``;

        if( product.varieties &&
            product.varieties.length > 0 &&
            product.varieties[0].images &&
            product.varieties[0].images.length > 0){
            //update the url to point at the image
            url = "." + product.varieties[0].images[0].path;
            alt = product.name;
        } else {
            //show the default vehicle
        }
        //${JSON.stringify(product)}
        innerHTML += `
            <div class="product-tile">
                <div class="product-image">
                    <img src="${url}" alt="${alt}" />
                </div>
                <div class="product-info">
                    <div class="product-identity">
                        <p><strong>${product.name}</strong></p>
                        <div onclick="showActionButtons(index)" class="product-options" title="More options">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                    <div class="product-buttons">
                        <p style="visibility:hidden;">Ksh. 2,500</p>
                        
                        <button title="Edit product details" class="product-edit-button" onclick="editProduct(${index})">EDIT</button>
                        <!-- <button onclick="showStats(true)" title="View vehicle current status" class="vehicle-stats-button text-white bg-primary">STATS</button> -->
                        <div class="action-buttons">
                            <!-- <button title="Edit product details" onclick='editProduct({})' class="">EDIT</button> -->
                            <button title="Delete product" onclick="deleteProduct(1)" class="bg-danger text-danger">DELETE</button>
                        </div>
                    </div>
                </div>
            </div>`;
    });

    //insert this into the innerHTML
    document.getElementsByClassName("products")[0].innerHTML = innerHTML;
}

let initialIndex, initialState = false;
const showActionButtons = index => {

    let vehicleTiles = document.getElementsByClassName('vehicle-tile'), i;
    let actionButtons = vehicleTiles[index].getElementsByClassName("action-buttons")[0];

    if(index == initialIndex){
        if(initialState == false){
            //Show the popup
            for(i=0; i<vehicleTiles.length; i++){
                vehicleTiles[i].getElementsByClassName("action-buttons")[0].style.display = "none";
            }
            actionButtons.style.display = "block";
            initialState = true;
        } else if (initialState == true){
            for(i=0; i<vehicleTiles.length; i++){
                vehicleTiles[i].getElementsByClassName("action-buttons")[0].style.display = "none";
            }
            initialState = false;
        }
        
    } else {
        //show the button
        for(i=0; i<vehicleTiles.length; i++){
            vehicleTiles[i].getElementsByClassName("action-buttons")[0].style.display = "none";
        }

        actionButtons.style.display = "block";
        initialState = true;
    }

    initialIndex = index;
}

const addVariety = event => {
    event.preventDefault();
    varieties = varieties.concat({
        images:[],
        name:undefined,
        description:undefined,
        price:undefined
    });

    /**Update the varieties UI */
    updateVarieties(varieties);
}

const updateVarieties = (varieties) => {
    //event.preventDefault();
    let varietiesContainer = document.getElementsByClassName("varieties")[0];

    /*varieties.forEach((element, index) => {
        if(element.images.length == 0){
            imagesHTML = `<div class="no-image">
                    <p>No Image</p>
                </div>`;
        } else {
            element.images.forEach((element, index2) => {
                imagesHTML += `<div class="single-image-preview">
                    <button title="Remove image" onclick="removeImage(${index}, ${index2}, event)" class="remove-button">&times;</button>
                    <img class="variety-image" src="" />
                </div>`;
            });
        }
    });*/
    const imagesHTML = (index, element) => {
        let innerHTML = ``;
        if(element.images)
            if(element.images && element.images.length == 0){
                return  `<div class="no-image">
                    <p>No Image</p>
                </div>`;
            } else {
                element.images.forEach((element2, index2) => {
                    innerHTML += `<div class="single-image-preview">
                        <button title="Remove image" onclick="removeImage(${index}, ${index2}, event)" class="remove-button">&times;</button>
                        <img class="variety-image" src="" />
                    </div>`;
                });

                return innerHTML;
            }
    }

    let innerHTML = ``;
    
    varieties.forEach((element, index) => {
        innerHTML += `<div class="variety">
            <h4>Variety ${index+1} <span onclick="deleteVariety(${index})" class="close-variety" title="Remove variety">&times;</span></h4>
            <label>Images <span class="danger">*</span></label>
            <div class="variety-images">
                <div class="image-preview">
                    ${imagesHTML(index, element)}
                </div>
                <input class="image-input" onchange="handleVarietyImageChange(${index}, event)" class="image-input" type="file" />
                <span class="image-input-error error"></span>
            </div>
            <div class="variety-details">
                <label>Variety Name<span class="danger">*</span></label>
                <div>
                    <input class="variety-name input" value="${element.name == undefined ? "" : element.name}" onchange="handleVarietyNameChange(${index}, event)" type="text" name="variety-name"/>
                    <span class="variety-name-error danger text-danger error"></span>
                </div>
                <label>Variety Price<span class="danger">*</span></label>
                <div>
                    <input class="variety-price input" value="${element.price == undefined ? "" : element.price}" onchange="handleVarietyPriceChange(${index}, event)" type="number" name="variety-price" />
                    <span class="variety-price-error danger text-danger error"></span>
                </div>
                <label>Variety Description<span class="danger">*</span></label>
                <div>
                    <textarea class="variety-description" onchange="handleVarietyDescriptionChange(${index}, event)" name="variety-description">${element.description == undefined ? "" : element.description}</textarea>
                    <span class="variety-description-error danger text-danger error"></span>
                </div>
            </div>
        </div>`;
    });

    varietiesContainer.innerHTML = innerHTML;

    /**
     * Update the images
     */
    let varietyElements = document.getElementsByClassName("variety");

    varieties.forEach((element, index) => {
        if(element.images)
            element.images.forEach((element2, index2) => {
                if(element2.id == undefined){
                    let reader = new FileReader();
                    reader.readAsDataURL(element2);
                    reader.onload = (event) => {
                        varietyElements[index].getElementsByClassName("variety-image")[index2].src = event.target.result;
                    }
                } else {
                    varietyElements[index].getElementsByClassName("variety-image")[index2].src = "." + element2.path;
                }
            });
    });
}

const deleteVariety = index => {
    /**
     * Only delete if varieties length > 1;
     */
    if(varieties.length > 1){
        if(varieties[index].id != undefined){
            deletedVarieties.push(varieties[index].id);
        }
        varieties.splice(index, 1);
        updateVarieties(varieties);
    }
}

const addSpecification = (event) =>{
    event.preventDefault();
    specifications = specifications.concat({
        groupName:undefined,
        propertiesAndValues:[
            {
                property:"",
                value:""
            }
        ]
    });

    /**Update the varieties UI */
    updateSpecifications(specifications);
}

const addPropertyAndValue = (specificationsIndex) => {
    specifications[specificationsIndex].propertiesAndValues.push({
        property:"",
        value:""
    });

    /**Update the varieties UI */
    updateSpecifications(specifications);
}

const removePropertyAndValue = (propertyAndValueIndex, specificationsIndex) => {
    if(specifications[specificationsIndex].propertiesAndValues[propertyAndValueIndex].id != undefined){
        deletedPropertyAndValues.push(specifications[specificationsIndex].propertiesAndValues[propertyAndValueIndex].id);
    }

    specifications[specificationsIndex].propertiesAndValues.splice(propertyAndValueIndex, 1);

    /**Update the varieties UI */
    updateSpecifications(specifications);
}

const updateSpecifications = (specifications) => {
    let specificationsContainer = document.getElementsByClassName("product-specifications")[0];

    let innerHTML = ``;
    
    specifications.forEach((element, index) => {
        innerHTML = innerHTML + `
            <div class="product-specifications-group">
                <input type="text" placeholder="Group Name" onchange="handleGroupNameChange(${index}, event)" value="${element.groupName == undefined ? "" : element.groupName}"/>
                <table>
                    <thead></thead>
                    <tbody>`;

                        element.propertiesAndValues.forEach((element2, index2) => {
                            innerHTML = innerHTML + `
                                <tr>
                                    <th><input type="text" placeholder="Propety Name" value="${element2.property == undefined ? "" : element2.property}" onchange="handlePropertyNameChange(${index2}, ${index}, event)"/></th>
                                    <td><input type="text" placeholder="Value" value="${element2.value == undefined ? "" : element2.value}" onchange="handleValueChange(${index2}, ${index}, event)"/></td>
                                    <td class="remove-specification-button"><button onclick="removePropertyAndValue(${index2}, ${index})"><img src="./assets/icons/Remove Icon.png" alt="remove icon" /></button></td>
                                </tr>
                            `;
                        });

        innerHTML = innerHTML + `</tbody>
                </table>
                <button class="add-specification-button" onclick="addPropertyAndValue(${index})"><img src="./assets/icons/Add icon.png" alt="add icon" /></button>
            </div>`;
    });

    specificationsContainer.innerHTML = innerHTML;
}

const addAdditionalInfo = (event) => {
    event.preventDefault();
    /*
        Store existing additional Info;
    */
    let holder;
    for(i=0; i<quill.length; i++){
        holder = {};

        holder.text = quill[i].getText();
        holder.html = quill[i].getSemanticHTML();
        holder.delta = quill[i].getContents();
        
        if(additionalInfos[i].id == undefined){
            additionalInfos[i] = holder;
            //quill[i].clipboard.dangerouslyPasteHTML(additionalInfos[i].html);
        } else {
            additionalInfos[i].html = quill[i].getSemanticHTML();
            additionalInfos[i].text = quill[i].getText();
            additionalInfos[i].delta = quill[i].getContents();
        }
    }
    additionalInfos = additionalInfos.concat({});
    /**Update the additional Informations */
    updateAdditionalInfos(additionalInfos);
}

const updateAdditionalInfos = (additionalInfos) => {
    console.log(additionalInfos);
    let additionalInfosContainer = document.getElementsByClassName("editors")[0];

    let editorHTML = `<div class="editor"></div><br/>`;

    let innerHTML = ``;
    
    additionalInfos.forEach((element, index) => {
        innerHTML = innerHTML + editorHTML;
    });
    
    additionalInfosContainer.innerHTML = innerHTML;

    const options = {
        placeholder: 'Hello, World!',
        theme: 'snow'
    };

    let editors = document.getElementsByClassName("editor"), i=0;

    var delta;
    console.log(editors);
    for(i=0; i<editors.length; i++){
        quill[i] = new Quill(editors[i], options);
        if(additionalInfos[i].delta){
            console.log(additionalInfos[i]);
            //quill[i].clipboard.dangerouslyPasteHTML(additionalInfos[i].html);
            quill[i].setContents(additionalInfos[i].delta);
        }
    }
}

const back = (event) => {
    event.preventDefault();
    categoriesSwiper.slidePrev();
}

const categorizeFurther = (event) => {
    event.preventDefault();
    categoriesSwiper.slideNext();
}