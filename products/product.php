<?php
    require "../api/db.php";
    require "../api/db-operations.php";
    /**
     * Fetch categorized products
     */
    /**
     * Fetches the available sensor categories from the database and returns a json response;
     */
    function getCategorizedProducts($host, $user, $password, $database){
        $sql = "SELECT * FROM product_categories WHERE deleted='0'";
        $productCategories = find($host, $user, $password, $database, $sql);
        $newArray = [];
        foreach ($productCategories as $index => $productCategory) {
            # code...
            $newArray[$index] = $productCategory;
        }

        $productCategories = $newArray;

        /**
         * Get all the products;
         */
        $products = getAllProducts($host, $user, $password, $database);
        /**
         * Fetch all the product-category relationships
         */
        $sql = "SELECT * FROM product_categories_relations WHERE deleted='0'";
        $productCategoriesRelations = find($host, $user, $password, $database, $sql);
        $newArray = [];
        foreach ($productCategoriesRelations as $index => $productCategoryRelation) {
            # code...
            $newArray[] = $productCategoryRelation;
        }

        $productCategoriesRelations = $newArray;

        $newArray = [];
        foreach ($productCategoriesRelations as $index => $productCategoryRelation) {
            # code...
            foreach ($products as $index2 => $product) {
                # code...
                if($productCategoryRelation["product_id"] == $product["id"]){
                    $product["category"] = $productCategoryRelation["category_id"];
                    $newArray[] = $product;
                }
            }
        }
        
        $products = $newArray;
        /*$rootCategories = [];
        foreach($productCategories as $index => $productCategory){
            if($productCategory["parent"] == 0){
                $rootCategories[] = generateCategoriesTree($productCategories[$index], $productCategories);

            }
        }
        
        $productCategories = [
            "id" => 0,
            "categories" => $rootCategories
        ];*/

        //$productCategories = generateFullCategoriesTree($productCategories);
        $productCategories = buildCategoryTreeWithProducts($productCategories, $products);

        $root = [
            "id" => 0,
            "categories" => $productCategories
        ];
        //return json_encode($root);
        return $root;
    }

    /**
     * Category Tree With Products;
     */
    function buildCategoryTreeWithProducts($categories, $products, $parentId = 0) {
        $tree = [];

        foreach ($categories as $category) {
            if ($category['parent'] == $parentId) {
                // Get all products that belong to the current category
                $categoryProducts = array_filter($products, function($product) use ($category) {
                    return $product['category'] == $category['id'];
                });

                // Recursively get child categories and products
                $children = buildCategoryTreeWithProducts($categories, $products, $category['id']);
                
                // Collect products from all child categories (flattening)
                $allChildProducts = array_reduce($children, function($carry, $child) {
                    return array_merge($carry, $child['products']);
                }, []);

                // Merge current category products with all child products
                $allProducts = array_merge(array_values($categoryProducts), $allChildProducts);

                $newArray = [];
                foreach ($allProducts as $index => $product) {
                    # code...
                    $exist = false;
                    foreach ($newArray as $index2 => $product2) {
                        # code...
                        if($product["id"] == $product2["id"]){
                            $exist = true;
                        }
                    }
                    if(!$exist){
                        unset($product["category"]);
                        $newArray[] = $product;
                    }
                }
                $allProducts = $newArray;
                // Build the category node
                $categoryNode = [
                    'id' => $category["id"],
                    'name' => $category['name'],
                    'parent' => $category['parent'],
                    'products' => $allProducts, // Include products from this category and all descendants
                    'categories' => $children // Recursive call for children
                ];

                // Add the category node to the tree
                $tree[] = $categoryNode;
            }
        }

        return $tree;
    }

    /**
     * Get all products
     */
    function getAllProducts($host, $user, $password, $database){
        /**
         * Get all the products
         */
        $sql = "SELECT * FROM products WHERE deleted='0'";
        $products = find($host, $user, $password, $database, $sql);

        $newArray = [];

        foreach ($products as $index => $product) {
            # code...
            unset($product["deleted"]);
            $newArray[] = $product;
        }

        $products = $newArray;

        
        /**
         * Get all the product categories;
         */
        $sql = "SELECT * FROM product_categories WHERE deleted='0'";
        $productCategories = find($host, $user, $password, $database, $sql);

        $newArray = [];

        foreach ($productCategories as $index => $productCategory) {
            # code...
            unset($productCategory["deleted"]);
            $newArray[] = $productCategory;
        }

        $productCategories = $newArray;

        foreach ($products as $index => $product) {
            /**
             * For each product
             */
            /**
             * Get the related specifications
             */
            $productId = $product["id"];

            $sql = "SELECT * FROM product_specifications WHERE deleted='0' AND product_id='$productId'";
            $productSpecifications = find($host, $user, $password, $database, $sql);

            $newArray = [];

            foreach ($productSpecifications as $index2 => $productSpecification) {
                # code...
                unset($productSpecification["deleted"]);
                $newArray[] = $productSpecification;
            }

            $productSpecifications = $newArray;

            /**
             * For each specification, get the property and values
             */

            foreach ($productSpecifications as $index2 => $productSpecification) {

                $productSpecificationId = $productSpecification["id"];
                

                $sql = "SELECT * FROM product_specifications_key_value_pairs WHERE deleted='0' AND product_specifications_id='$productSpecificationId'";
                $productSpecificationsKeyValuePairs = find($host, $user, $password, $database, $sql);

                $newArray = [];

                foreach ($productSpecificationsKeyValuePairs as $index3 => $productSpecificationsKeyValuePair) {
                    # code...
                    unset($productSpecificationsKeyValuePair["deleted"]);
                    $newArray[] = $productSpecificationsKeyValuePair;
                }

                $productSpecificationsKeyValuePairs = $newArray;
                $productSpecifications[$index2]["propertiesAndValues"] = $productSpecificationsKeyValuePairs;

                $productSpecifications[$index2]["groupName"] = $productSpecifications[$index2]["group_name"];
                unset($productSpecifications[$index2]["group_name"]);
            }

            $products[$index]["specifications"] = $productSpecifications;

            /**
             * Get all the product-category-relations;
             */
            $sql = "SELECT * FROM product_categories_relations WHERE deleted='0' AND product_id='$productId'";
            $productCategoriesRelations = find($host, $user, $password, $database, $sql);

            $newArray = [];

            foreach ($productCategoriesRelations as $index2 => $productCategoriesRelation) {
                # code...
                
                foreach ($productCategories as $index3 => $productCategory) {
                    if($productCategory["id"] == $productCategoriesRelation["category_id"]){
                        $newArray[] = $productCategory;
                    }
                }
            }

            $productCategoriesRelations = $newArray;
            $products[$index]["categories"] = $productCategoriesRelations;

             /**
              * Get the product varieties
              */
            $sql = "SELECT * FROM product_varieties WHERE deleted='0' AND product_id='$productId'";
            $productVarieties = find($host, $user, $password, $database, $sql);

            $newArray = [];

            foreach ($productVarieties as $index2 => $productVariety) {
                # code...
                unset($productVariety["deleted"]);
                $newArray[] = $productVariety;
            }

            $productVarieties = $newArray;

            foreach ($productVarieties as $index2 => $productVariety) {
                /**
                 * For each product variety get the images
                 */
                $productVarietyId = $productVariety["id"];

                $sql = "SELECT * FROM images WHERE variety_id='$productVarietyId' AND deleted=0";
                $images = find($host, $user, $password, $database, $sql);
                
                $newArray = [];
                foreach ($images as $index3 => $image) {
                    # code...
                    unset($image["deleted"]);
                    $newArray[] = $image;
                }
                $images = $newArray;

                foreach ($images as $index3 => $image) {
                    # code...
                    $slashes =  "/". "uploads"."/";
                    $images[$index3]["path"] = $slashes . $images[$index3]["path"];
                }
                $productVarieties[$index2]["images"] = $images;
            }
            $products[$index]["varieties"] = $productVarieties;

            /**
             * Get additional Infos for the product
             */
            $sql = "SELECT * FROM articles WHERE deleted='0' AND product_id='$productId'";
            $articles = find($host, $user, $password, $database, $sql);

            $newArray = [];

            foreach ($articles as $index2 => $article) {
                # code...
                unset($article["deleted"]);
                $newArray[] = $article;
            }

            $articles = $newArray;
            $products[$index]["articles"] = $articles;
        }

        //return $products;
        return $products;
    }

    $products = getAllProducts($host, $user, $password, $database);
    $activeProduct = [];
    if(isset($_GET["product-id"])){
        $productId = $_GET["product-id"];
        foreach ($products as $key => $product) {
            # code...
            if($productId == $product["id"]){
                $activeProduct = $product;
            }
        }
    }
    $activeProductJSON = json_encode($activeProduct);
    $categorizedProducts = getCategorizedProducts($host, $user, $password, $database);
    $categorizedProductsJSON = json_encode($categorizedProducts);

    // Recursive function to search for a category by ID or name
    function findCategory($tree, $searchValue, $searchBy = 'id') {
        // If the current category matches the search value, return it
        if (isset($tree) && $tree[$searchBy] == $searchValue) {
            return $tree;
        }
        
        // If the current category has subcategories, search recursively
        if (isset($tree['categories']) && is_array($tree['categories'])) {
            foreach ($tree['categories'] as $subcategory) {
                $result = findCategory($subcategory, $searchValue, $searchBy);
                if ($result) {
                    return $result; // Return the found category
                }
            }
        }
        
        return null; // Return null if the category was not found
    }

    foreach ($activeProduct["categories"] as $key => $category) {
        # code...
        $activeProduct["categories"][$key] = findCategory($categorizedProducts, $category["id"]);
    }
    
    $relatedProducts = [];
    foreach ($activeProduct["categories"] as $index => $category) {
        # code...
        foreach ($activeProduct["categories"][$index]["products"] as $index2 => $categoryProduct) {
            # code...
            if($categoryProduct["id"] != $productId){
                /**
                 * If product is already in the array
                 */
                $productPresent = false;
                foreach ($relatedProducts as $index3 => $relatedProduct) {
                    # code...
                    if($relatedProduct["id"] == $categoryProduct["id"]){
                        $productPresent = true;
                    }
                }

                if(!$productPresent){
                    $relatedProducts[] = $categoryProduct;
                }
            }
        }
    }
    $relatedProductsJSON = json_encode($relatedProducts);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../scripts/swiper/swiper-bundle.min.css" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="../styles/global.css" />
    <link rel="stylesheet" type="text/css" href="../styles/footer.css" />
    <link rel="stylesheet" type="text/css" href="../styles/header.css" />
    <link rel="stylesheet" type="text/css" href="../styles/products.css" />
    <link rel="stylesheet" type="text/css" href="../styles/product.css" />

    <?php 
        echo "<script> 
            let  product=$activeProductJSON, relatedProducts=$relatedProductsJSON;
            console.log(product);
            console.log(relatedProducts);
        </script>";
    ?>
    <?php
        $productName = $activeProduct["name"];
        $productDescription = $activeProduct["description"];
        $productDescription = html_entity_decode($productDescription);
    ?>
    <meta name="description" content="<?php echo $productDescription ?>" />
    <title> <?php echo $productName?></title>
</head>
<body>
    <header class="navigation">
        <div class="menu">
            <div class="logo">
                <div 
                    class="menu-bars large-screen-menu-bars" 
                    id="navigation-bars" 
                    onclick="toggleNavigation(true)" >
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <a href="../"><img class="beko-logo" src="../assets/icons/Beko Corporate Solutions Logo BLUE.png" alt="Beko logo white" /></a>
            </div>
            
            <ul class="menu-items">
                <div class="our-services">
                    <span class="dynamic-text">Products</span>
                    <div>
                        <ul>
                            <?php
                                $innerHTML = "";
                                foreach ($categorizedProducts["categories"] as $index => $category) {
                                    # code...
                                    $categoryId = $category["id"];
                                    $categoryName = $category["name"];
                                    if($categoryId == 1){
                                        $innerHTML .= "<a href='https://www.beko.com/ke-en' target='_blank'>$categoryName</a>";
                                    } else {
                                        if(count($category["categories"]) > 0){
                                            $innerHTML .= "<span>$categoryName</span><ul>";

                                            foreach($category["categories"] as $index2 => $category){
                                                $categoryId = $category["id"];
                                                $categoryName = $category["name"];
                                                $innerHTML .= "<a href='../products.php?category-id=$categoryId'>$categoryName</a>";
                                            }

                                            $innerHTML .= "</ul>";
                                        } else {
                                            $innerHTML .= "<a href='../products.php?category-id=$categoryId'>$categoryName</a>";
                                        }
                                    }
                                    
                                }
                                echo $innerHTML;
                            ?>
                        </ul>
                    </div>
                </div>
                <a href="../about-us.php" class="dynamic-text">About Us</a>
                <a class="dynamic-text" href="../contact-us.php" onclick="setFormSubject('RE: General Inquiry', 'Hello, \nI would like to inquire about\n')">Contact Us</a>
            </ul>
            <div 
                class="menu-bars small-screen-menu-bars" 
                id="navigation-bars" 
                onclick="toggleNavigation(true)" >
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="hamburger-menu">
            <div id="hamburger-menu-container">
                <ul class="hamburger-menu-ul">
                    <span title="Close" class="close-hamburger-menu" onclick="toggleNavigation(false)">&times;</span>
                    <a href="../">Home</a>
                    <a href="../about-us.php">About Us</a>
                    <a href="../contact-us.php" onclick="setFormSubject('RE: General Inquiry', 'Hello, \nHow I would like to inquire about\n')">Contact Us</a>
                    <?php
                        $innerHTML = "";
                        foreach ($categorizedProducts["categories"] as $index => $category) {
                            # code...
                            $categoryId = $category["id"];
                            $categoryName = $category["name"];
                            $innerHTML .= "<li>";
                            if($categoryId == 1){
                                $innerHTML .= "<span><a href='https://www.beko.com/ke-en' target='_blank'>$categoryName</a></span>";
                            } else {
                                $innerHTML .= "<span><a href='../products.php?category-id=$categoryId'>$categoryName</a></span>";
                            }
                            
                            $innerHTML .="
                                <div class='underline'></div>
                                <ul>";
                                    if($categoryId != 1){
                                        foreach ($category["categories"] as $key => $subCategory) {
                                            # code...
                                            $subCategoryId = $subCategory["id"];
                                            $subCategoryName = $subCategory["name"];
                                            $innerHTML .= "<a href='../products.php?category-id=$subCategoryId'>$subCategoryName</a>";
                                        }
                                    }
                            $innerHTML .="</ul>
                            </li>";
                        }
                        echo $innerHTML;
                    ?>
                    <!-- <li>
                        <span>Built In Home Appliances</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="careers.html">Careers</a>
                            <a href="environmental-and-health-safety-at-work-policy.html">EHS Policy</a>
                        </ul>
                    </li> -->
                </ul>
            </div>
        </div>
    </header>
    <main>

        <?php
            $productName = $activeProduct["name"];
            $productDescription = $activeProduct["description"];
            $productDescription = html_entity_decode($productDescription);
        ?>
        <!-- <div class="curved-background"></div> -->
        <!-- <p class="breadcrumbs"> <a href="/">Home</a> / <a href="/products.html">Products</a> / <a href="/products/product.html">Product Name</a></p> -->
        <section class="single-product-details">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php
                        $innerHTML = "";
                        foreach ($activeProduct["varieties"][0]["images"] as $index => $image) {
                            # code...
                            $imagePath = "../api" . $image["path"];
                            $innerHTML .= "
                                <div class='swiper-slide'>
                                    <div class='single-product-image-container'>                
                                        <img class='single-product-image' src='$imagePath' alt='Built in microwave' />
                                    </div>
                                </div>
                            ";
                        }
                        echo $innerHTML;

                    ?>
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <!-- <div class="single-product-image-container">                
                <img class="single-product-image" src="../assets/9200093200-LO1-20210425-154728.png" alt="Built in microwave" />
            </div> -->
            <div class="single-product-information">
                <h1><?php  echo $productName ?> <a onclick='shareSingleProduct(event)' href="#"><img src="../assets/icons/share icon.png" alt="share-icon" /></a></h1>

                <div class="single-product-description">
                    <h2>Description</h2>
                    <p><?php echo $productDescription?></p>
                </div>
                <div class="single-product-varieties">
                    <h2>Varieties</h2>
                    <div class="varieties">

                        <?php
                            $innerHTML = "";
                            foreach ($activeProduct["varieties"] as $index => $variety) {
                                # code...
                                $varietyImage = "../api/" . $variety["images"][0]["path"];
                                $varietyName = $variety["name"];

                                $innerHTML .= "<div>
                                        <div><img src='$varietyImage' /></div>
                                        <p>$varietyName</p>
                                    </div>";
                            }
                            echo $innerHTML;
                        ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
            $productPath = $_SERVER['REQUEST_URI'];
        ?>
        <section class="contact-us-and-specifications">
            <div class="contact-us-component" style="display: none;">
                <div><a href="https://api.whatsapp.com/send?phone=+254768444404&text=Hi Beko Corporate Solutions,\n I'd like to inquire about <?php echo $productName?>\n <?php echo $productPath?>" target="_blank"><span><img src="../assets/icons/Whatsapp icon.png" alt="whatsapp icon" /></span> <span>Contact Us On Whatsapp</span></a></div>
                <div><a href="mailto:bekocorporate@koch.co.ke?subject=Inquiry on [Product Name]&body=Hi Beko Corporate Solutions,\nI'd like to inquire about <?php echo $productName?>, <?php echo $productPath?>." target="_blank"><span><img src="../assets/icons/gmail icon.png" alt="email icon" /></span> <span>Email us</span></a></div>
                <div><a href="tel:+254768444404"><span><img src="../assets/icons/phone icon.png" alt="phone icon" /></span> <span>Call us</span></a></div>
            </div>

            <div class="specifications">
                <?php 
                    $innerHTML = "";
                    $specificationsName = $activeProduct["specifications"][0]["groupName"];

                    $innerHTML .= "<h2>$specificationsName</h2>
                        <div class='underline'></div>
                            <table>
                                <tbody>";
                    foreach ($activeProduct["specifications"] as $index => $specification) {
                        # code...
                        if($index == 0 ){
                            foreach ($specification["propertiesAndValues"] as $index2 => $propertyAndValue) {
                                # code...
                                $property = $propertyAndValue["property"];
                                $value = $propertyAndValue["value"];

                                if($value == ""){
                                    $value = "<img class='checked-icon' src='../assets/icons/checked.png' alt='checked icon' />";
                                }
                                $innerHTML .= "<tr>
                                        <th>$property</th><td>$value</td>
                                    </tr>";
                            }
                        }
                    }
                    $innerHTML .= "</tbody>
                        </table>";

                    echo $innerHTML;
                ?>
                <!-- <h2>Dimensions and Weight</h2>
                <div class="underline"></div>
                <table>
                    <tbody>
                        <tr>
                            <th>Length</th><td>20 cm</td>
                        </tr>
                        <tr>
                            <th>Width</th><td>20 cm</td>
                        </tr>
                        <tr>
                            <th>Height</th><td>20 cm</td>
                        </tr>
                        <tr>
                            <th>Weight</th><td>20 kg</td>
                        </tr>
                    </tbody>
                </table> -->
            </div>
        </section>
        <?php
            $innerHTML = "";
            /*$varietyName = $activeProduct["specifications"][0]["groupName"];

            $innerHTML .= "<h2>$varietyName</h2>
                <div class='underline'></div>
                    <table>
                        <tbody>";*/
            foreach ($activeProduct["specifications"] as $index => $specification) {
                # code...
                if($index == 0){
                    continue;
                }
                $specificationsName = $specification["groupName"];
                $innerHTML .= "<div class='specifications'>
                    <h2>$specificationsName</h2>
                    <div class='underline'></div>
                    <table>
                        <tbody>";

                    foreach ($specification["propertiesAndValues"] as $index2 => $propertyAndValue) {
                        # code...
                        $property = $propertyAndValue["property"];
                        $value = $propertyAndValue["value"];

                        if($value == ""){
                            $value = "<img class='checked-icon' src='../assets/icons/checked.png' alt='checked icon' />";
                        }
                        $innerHTML .= "<tr>
                                <th>$property</th><td>$value</td>
                            </tr>";
                    }

                $innerHTML .= "</tbody>
                    </table>
                </div>";
            }
            echo $innerHTML;
        ?>
        <!-- <div class="specifications">
            <h2>Other Specifications</h2>
            <div class="underline"></div>
            <table>
                <tbody>
                    <tr>
                        <th>Property</th><td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloremque, minima porro. Eaque dolores sit dolorum cum enim quidem unde numquam vitae sunt velit, ut temporibus laborum nesciunt quibusdam aut cumque.</td>
                    </tr>

                    <tr>
                        <th>Property</th><td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloremque, minima porro. Eaque dolores sit dolorum cum enim quidem unde numquam vitae sunt velit, ut temporibus laborum nesciunt quibusdam aut cumque.</td>
                    </tr>
                    <tr>
                        <th>Property</th><td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloremque, minima porro. Eaque dolores sit dolorum cum enim quidem unde numquam vitae sunt velit, ut temporibus laborum nesciunt quibusdam aut cumque.</td>
                    </tr>
                    <tr>
                        <th>Property</th><td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloremque, minima porro. Eaque dolores sit dolorum cum enim quidem unde numquam vitae sunt velit, ut temporibus laborum nesciunt quibusdam aut cumque.</td>
                    </tr>
                    
                </tbody>
            </table>
        </div> -->
        <div class="more-like-this">
            <h2>You May Also Like</h2>
            <div class="underline"></div>
        </div>
        <section class="products">
        <?php
                function getProductImage($product){
                    return "../api" . $product["varieties"][0]["images"][0]["path"];
                }

                if(isset($relatedProducts)){
                    foreach ($relatedProducts as $index => $relatedProduct) {
                        # code...
                        $productImage = getProductImage($relatedProduct);
                        $productName = $relatedProduct["name"];
                        $productDescription = $relatedProduct["description"];
                        $productPath = "./product.php?product-id=" . $relatedProduct["id"];
                        echo "<div class='product'>
                                <div class='product-image-container'>
                                    <img src='$productImage' alt='e-commerce image' />
                                </div>
                                <div class='product-info' style='visibility: hidden;'>
                                    <div class='product-name'>
                                        <img src='./assets/icons/product icon.png' alt='product icon' />
                                        <p>$productName</p>
                                        <div class='share-icon-container'>
                                            <a href=''><img src=''../assets/icons/share icon.png' alt='share-icon' /></a>
                                        </div>
                                    </div>
                                    <a href='$productPath'>More Info...</a>
                                </div>
                                <div class='product-info'>
                                    <div class='product-name'>
                                        <img src='../assets/icons/product icon.png' alt='product icon' />
                                        <p>$productName</p>
                                        <div class='share-icon-container'>
                                            <a onclick='shareRelatedProduct($index, event)' href='#'><img src='../assets/icons/share icon.png' alt='share-icon' /></a>
                                        </div>
                                    </div>
                                    <div class='product-details'>
                                        <img src='../assets/icons/product info icon.png' alt='product info icon' />
                                        <p>$productDescription</p>
                                    </div>
                                    <a href='$productPath'>More Info...</a>
                                </div>
                            </div>";
                    }
                }
            ?>
            <!-- <div class="product">
                <div class="product-image-container">
                    <img src="../assets/9200093200-LO1-20210425-154728.png" alt="e-commerce image" />
                </div>
                <div class="product-info"style="visibility: hidden;">
                    <div class="product-name">
                        <img src="../assets/icons/product icon.png" alt="product icon" />
                        <p>Built-in Microwave (900 W, 25 L)</p>
                        <div class="share-icon-container">
                            <a href="#"><img src="../assets/icons/share icon.png" alt="share-icon" /></a>
                        </div>
                    </div>
                    <a href="./products/product.html">More Info...</a>
                </div>
                <div class="product-info">
                    <div class="product-name">
                        <img src="../assets/icons/product icon.png" alt="product icon" />
                        <p>Built-in Microwave (900 W, 25 L)</p>
                        <div class="share-icon-container">
                            <a href="#"><img src="../assets/icons/share icon.png" alt="share-icon" /></a>
                        </div>
                    </div>
                    <div class="product-details">
                        <img src="../assets/icons/product info icon.png" alt="product info icon" />
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Iure nisi velit ullam delectus voluptatum laborum quo placeat officia voluptatibus obcaecati. Modi asperiores similique fugiat laborum commodi. Sed sint quod odit!</p>
                    </div>
                    <a href="./products/product.html">More Info...</a>
                </div>
            </div> -->
            
        </section>
        <div class="floating-whatsapp-icon">
            <a href="https://api.whatsapp.com/send?phone=+254768444404&text=Hi Beko Corporate Solutions, I'd like to inquire about <?php echo $productName?> <?php echo "https://" . $_SERVER["HTTP_HOST"] .  $_SERVER['REQUEST_URI']?>" target="_blank">
                <span><img src="../assets/icons/Whatsapp icon.png" alt="whatsapp icon" /></span>
            </a>
        </div>
    </main>
    <footer >
        <div class="footer-content-wrap">
            <div class="brands">
                <div class="brand-logo-name">
                    <div class="brand-logo">
                        <img src="../assets/icons/Beko Corporate Solutions Logo BLUE.png" alt="Beko Corporate Solutions Blue Logo" />
                    </div> 
                </div>
                <div class="brands-paragraph-content">
                    <p>We work for a sustainable future through our technology, human resources, and production power.
                    Our vision is to rejuvenate ourselves and our industry to become a trusted lifestyle solutions provider to the digital household.</p>
                </div> 
                <div class="social-media-brand-logos">
                    <div>
                        <a href="https://www.instagram.com/bekocorporate_solutions/" target="_blank">
                            <img src="../assets/icons/instagram icon.png" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="quick-links">
                <h3>Quick Links</h3>
                <a href="../index.php">Home</a>
                 <?php
                    $innerHTML = "";
                    foreach ($categorizedProducts["categories"] as $index => $category) {
                        # code...
                        $categoryId = $category["id"];
                        $categoryName = $category["name"];
                        if($categoryId == 1){
                            $innerHTML .= "<a href='https://www.beko.com/ke-en' target='_blank'>$categoryName</a>";
                        } else {
                            $innerHTML .= "<a href='../products.php?category-id=$categoryId'>$categoryName</a>";
                        }
                        
                    }
                    echo $innerHTML;
                ?>
                <a href="../about-us.php">About Us</a>
                <a href="../contact-us.php">Contact Us</a>
            </div>
            <div class="contact">
                <h3>Contacts</h3> <br />
                <div class="contact-information-icons-details">
                    <div class="phone-details">
                        <div class="icon-container">
                            <img src="../assets/icons/phone-white-icon.png" />
                        </div>
                        <div class="phone-link">
                            <a href="tel:+254768444404">+254 768 444 404</a>
                        </div>
                    </div>
                    <div class="email-details">
                        <div class="icon-container">
                            <img src="../assets/icons/email-white-icon.png" />
                        </div>
                        <div class="email-link">
                            <a href="mailto:bekocorporate@Koch.co.ke">bekocorporate@koch.co.ke</a>
                        </div>
                    </div>
                    <div class="address-details">
                        <div class="icon-container">
                            <img src="../assets/icons/address-white-icon.png" />
                        </div>
                        <div class="address-link">
                            <p>
                                Ground Floor, Apollo Center <br />
                                Ring Road,  Nairobi – Kenya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="news-letter">
                <h3>Subscribe to our Email</h3><br />
                <h4>For Latest News and Updates</h4><br />
                <div class="form-container">
                    <form id="form" onsubmit="subscribeToOurEmail(event)">
                        <input onchange="handleEmailChange(event)" type="email" id="email" name="email" placeholder="youremail.example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"  required />
                        <input type="Submit" id="subscribe" value="Subscribe"> 
                    </form>
                </div>
            </div>
        </div>
        <div class="copyright-tag">
            <p>Copyright &copy; <span id="year">
            </span> | Beko Corporate Solutions | Maintained by <span>
                <a href="https://www.yosambranding.art" target="_blank">Yosam Branding</a>
            </span>
            </p>
        </div>
    </footer>
    
    <script type="text/javascript" src="../scripts/navigation.js"></script>
    <script type="text/javascript" src="../scripts/footer.js"></script>
    <script type="text/javascript" src="../scripts/current-year.js"></script>
    <script type="text/javascript" src="../scripts/share-product.js"></script>
    <script type="text/javascript" src = "../scripts/swiper/swiper-bundle.min.js"></script>
    <script type="text/javascript" src = "../scripts/swiper/initialize-swiper-multiple.js"></script>
    <script type="text/javascript" src="../scripts/swiper/initialize-swiper.js"></script>
</body>
</html>