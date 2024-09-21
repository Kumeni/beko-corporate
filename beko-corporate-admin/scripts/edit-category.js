let category;
let activeCategory, activeCategories;
let categories;
let parentCategory;

const editCategory = category => {
    //First show the popup
    //Just that;
    let categoryInput = document.getElementById("category-input");
    let popup = document.getElementsByClassName("popup")[0];
    let editCategoryButton = document.getElementById("edit-category-button");

    if(category == undefined){
        //Close the popup
        popup.style.visibility = "hidden";
        popup.style.zIndex = "-10";
        activeCategory = undefined;
        categoryInput.value = null;
        editCategoryButton.value = "CREATE";
    } else {
        //If it editing and existing category the
        if(category.id){
            categoryInput.value = category.name;
            editCategoryButton.value = "EDIT";
            /**
             * Get the parent and install in the parentCategory;
             */
        }
        console.log(category);
        activeCategory = category;
        popup.style.visibility = "visible";
        popup.style.zIndex = "10";
    }

    //console.log(activeCategory);
}

const handleCategoryChange = event => {
    activeCategory.name = event.target.value;
    document.getElementById("category-error").innerHTML = "";
}

const handleCategoryUpload = event => {
    event.preventDefault();
    let canSubmit = true;
    //Only signedin users can upload vehicles
    //if(credentials == null || credentials == undefined) return;

    //Category is required
    if(activeCategory.name == undefined || activeCategory.name == null) {
        canSubmit = false;
        document.getElementById("category-error").innerHTML = "Product category is required!";
    }

    if(canSubmit == false) return;

    console.log(activeCategory);
    let formData = new FormData();
    formData.append("category", activeCategory.name);
    formData.append("parent", activeCategory.parent);
    //formData.append("parent", parentCategory.id);

    if(activeCategory.id != undefined) formData.append("id", activeCategory.id);

    //formData.append("credentials", credentials);
    const API_ENDPOINT = "./server/product-categories.php";
    const request = new XMLHttpRequest();

    request.open("POST", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            categories = JSON.parse(request.response);
            editCategory();
            updateAllCategories(categories);
            //get the active categoryFrom the categories/
            //get its parent
            //updateAllCategories(getCategoryFromTree(activeCategories));
            //updatePrimaryCategories(categories);
        }
    }

    request.send(formData);
    /**
     * Validate the data to be sent;
     * Send the data to the server for processing;
     * Close the upload on completion
     */    
}

const getCategories = () => {
    const API_ENDPOINT = "./server/product-categories.php";
    const request = new XMLHttpRequest();

    request.open("GET", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            categories = JSON.parse(request.response);
            //parentCategory = categories;
            activeCategory = categories;
            editCategory();
            /**
             * Update the categories
            */
            activeCategories = generate2DArrayFromTreeDataStructure(categories);
            updatePrimaryCategories(generate2DArrayFromTreeDataStructure(categories));
        }
    }

    request.send();
}
getCategories();

const deleteCategory = categoryId => {
    //let formData = new FormData();
    //formData.append("deleted", JSON.stringify([categoryId]));
    //formData.append("id", categoryId);
    //formData.append("credentials", credentials);
    const API_ENDPOINT = "./server/product-categories.php";
    const request = new XMLHttpRequest();

    request.open("DELETE", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            categories = JSON.parse(request.response);
            editCategory();
            updateAllCategories(categories);
            //updatePrimaryCategories(categories);
        }
    }

    request.send(JSON.stringify({id:categoryId}));
}

const updateCategories = categories => {
    if(categories == undefined)
        return;

    let categoriesContainer = document.getElementsByClassName("categories")[0];
    let innerHTML = ``;

    categories.forEach((element, index) => {
        innerHTML += `
            <li>
                <span>${index+1}. ${element.category}</span>
                <button title="Delete category" onclick="deleteCategory(${element.id})">&times;</button>
                <button onclick='editCategory(${JSON.stringify(element)})' title="Edit Category">Edit</button>
            </li>
        `;
    });

    categoriesContainer.innerHTML = innerHTML;
}

const updatePrimaryCategories = categories => {
    let categoriesContainer = document.getElementsByClassName("categories")[0];
    let innerHTML = ``;

    innerHTML += `<ul class="primary-categories">`;
    if(categories[1] == undefined) categories[1] = [];

    categories[1].forEach((category, index2) => {
        innerHTML += `<li onclick='updateAllCategories(${JSON.stringify(category)})'>
            ${category.name}
            <span>
                <img onclick='editCategory(${JSON.stringify(category)})' src="./assets/icons/edit icon.png" alt="edit icon"/>
                    <img onclick='deleteCategory(${category.id})'src="./assets/icons/delete icon.png" alt="delete icon"/>
            </span>
        </li>`;
    });

    innerHTML += `<button onclick='editCategory({"parent":0})' class="create-category-button">Create Category</button>`;
    innerHTML += `</ul>`;

    categoriesContainer.innerHTML = innerHTML;
}

const updateAllCategories = (activeCategory) => {
    
    let categoriesContainer = document.getElementsByClassName("categories")[0];
    let innerHTML = ``, i;

    let treeCategory = getCategoryFromTree(activeCategory, categories);
    //let flattenedCategories = generate2DArrayFromTreeDataStructure(categories);
    let flattenedCategories = activeCategories;
    let flattenedTreeCategories = generate2DArrayFromTreeDataStructure(treeCategory);
    let newFlattenedCategories = [];

    console.log(flattenedTreeCategories);
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

    // console.log(newFlattenedCategories);
    for(i=0; i<flattenedTreeCategories.length; i++){
        if(i==0 || i>1) continue;
        newFlattenedCategories.push(flattenedTreeCategories[i]);
    }
    //newFlattenedCategories.push(flattenedTreeCategories[1]);
    /**
     * Create the button to add new sub-category;
     */

    let parentName = "", parentId;
    if(treeCategory.categories == undefined){
        newFlattenedCategories.push([]);
        parentName = treeCategory.name;
        parentId = treeCategory.id;
    }

    flattenedCategories = newFlattenedCategories;
    activeCategories = flattenedCategories;

    for(i=0; i<flattenedCategories.length; i++){
        if(i == 0) continue;
        
        innerHTML += `<ul>`;

        if(i >= 2){
            if(flattenedCategories[i].length == 0){
                innerHTML += `<p>${parentName}</p>`;
            } else {
                innerHTML += `<p>${getParentName(flattenedCategories[i][0], flattenedCategories)}</p>`;
            }
        }
        
        flattenedCategories[i].forEach((category, index2) => {
            innerHTML += `<li onclick='updateAllCategories(${JSON.stringify(category)})'>
                ${category.name}
                <span>
                    <img onclick='editCategory(${JSON.stringify(category)})' src="./assets/icons/edit icon.png" alt="edit icon"/>
                    <img onclick='deleteCategory(${category.id})'src="./assets/icons/delete icon.png" alt="delete icon"/>
                </span>
            </li>`;
        });

        if(flattenedCategories[i].length == 0){
            innerHTML += `<button onclick='editCategory({"parent":${parentId}})' class="create-category-button">Create Category</button>`;
        } else {
            innerHTML += `<button onclick='editCategory({"parent":${flattenedCategories[i][0].parent}})' class="create-category-button">Create Category</button>`;
        }
        //innerHTML += `<button onclick='editCategory({"parent":${activeCategory.id}})' class="create-category-button">Create Category</button>`;
        innerHTML += `</ul>`;
    }

    categoriesContainer.innerHTML = innerHTML;
}

const getParentName = (category, flattenedCategories) => {
    let i, j;

    for(i=0; i<flattenedCategories.length; i++){
        for(j=0; j<flattenedCategories[i].length; j++){
            if(category.parent == flattenedCategories[i][j].id)
                return flattenedCategories[i][j].name;
        }
    }
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
    console.log(tree);
    const getAnElementFromTreeDataStructure = (target, tree) =>{
        console.log(tree);
        if(tree.id == target.id) treeCategory = tree;
        
        if(tree.categories)
            tree.categories.forEach((category, index) => {
                getAnElementFromTreeDataStructure(target, category);
            });
    }
    getAnElementFromTreeDataStructure(category, tree);
    return treeCategory;
}
/**
 * General function that generates the full 2D array
 * Another function that generates a specific 2D array
 */