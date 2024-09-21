<?php

    require "./beko-corporate-admin/server/db.php";
    require "./beko-corporate-admin/server/db-operations.php";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./styles/global.css" />
    <link rel="stylesheet" type="text/css" href="./styles/footer.css" />
    <link rel="stylesheet" type="text/css" href="./styles/header.css" />
    <link rel="stylesheet" type="text/css" href="./styles/products.css" />

    <?php 
        echo "<script>let categorizedProducts=$categorizedProductsJSON;</script>";
    ?>
    <title>Products</title>
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
                <a href="./"><img class="beko-logo" src="./assets/icons/white beko logo.png" alt="Beko logo white" /></a>
            </div>
            <div class="our-services">
                <span class="dynamic-text">Products <span class="outer-circle"><span class="inner-circle"></span></span></span>
                <div>
                    <ul>
                        <a href="./products.php?category-id=1">Built In Home Appliances</a>
                        <a href="./products.php?category-id=2">Solar Panel & Products</a>
                        <a href="./products.php?category-id=3">AC Solutions</a>
                        <a href="./products.php?category-id=4">Hotel Concepts</a>
                        <a href="./products.php?category-id=5">Kitchen Cabinets</a>
                        <a href="./products.php?category-id=6">Wardrobes</a>
                        <a href="./products.php?category-id=7">Doors</a>
                        <a href="./products.php?category-id=8">Corporate Scenting Solutions</a>
                        <a href="./products.php?category-id=9">EV Chargers</a>
                    </ul>
                </div>
            </div>
            <ul class="menu-items">
                <a href="./about-us.html" class="dynamic-text">About Us</a>
                <a class="dynamic-text" href="./contact-us.html" onclick="setFormSubject('RE: General Inquiry', 'Hello, \nHow I would like to inquire about\n')">Contact Us</a>
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
                    <a href="./our-projects.html">Home</a>
                    <a href="./about-us.html">About Us</a>
                    <li>
                        <span>Built In Home Appliances</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="careers.html">Careers</a>
                            <a href="environmental-and-health-safety-at-work-policy.html">EHS Policy</a>
                        </ul>
                    </li>
                    <li>
                        <span>Solar Panel & Products</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>Solar Panel & Products</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>AC Solutions</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>Hotel Concepts</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>Kitchen Cabinets</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>Wardrobes</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>Doors</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>Corporate Scenting Solutions</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <li>
                        <span>EV Chargers</span>
                        <div class="underline"></div>
                        <ul>
                            <a href="./services/residential-solar-system-solutions.html">Residential Solar Systems Solutions</a>
                            <a href="./services/commercial-solar-system-solutions.html">Commercial Solar System Solutions</a>
                        </ul>
                    </li>
                    <a href="./contact-us.html" onclick="setFormSubject('RE: General Inquiry', 'Hello, \nHow I would like to inquire about\n')">Contact Us</a>
                </ul>
            </div>
        </div>
    </header>

    <main>
        
        <?php
            if(isset($_GET["category-id"])){
                $categoryId = $_GET["category-id"];
                $category = findCategory($categorizedProducts, $categoryId, $searchBy = 'id');
                $categoryName = $category["name"];
                $categoryJSON = json_encode($category);
                $products = $category["products"];
                $productsJSON = json_encode($products);

                echo "<script>let category =$categoryJSON, products=$productsJSON;</script>";

                /**
                 * Display categorized products
                 */
                function getProductImage($product){
                    return "./beko-corporate-admin" . $product["varieties"][0]["images"][0]["path"];
                }
            }
        ?>
        <section class="landing">
            <h1><?php echo $categoryName ?></h1>
            <div class="underline"></div>
            <div class="curved-background"></div>
        </section>
        <p class="breadcrumbs"> <a href="/">Home</a> / <a href="/products.html">Products</a></p>
        <section class="products">

            <?php
                if(isset($products)){
                    foreach ($products as $index => $product) {
                        # code...
                        $productImage = getProductImage($product);
                        $productName = $product["name"];
                        $productDescription = $product["description"];
                        $productPath = "./products/product.php?product-id=" . $product["id"];
                        echo "<div class='product'>
                                <div class='product-image-container'>
                                    <img src='$productImage' alt='e-commerce image' />
                                </div>
                                <div class='product-info' style='visibility: hidden;'>
                                    <div class='product-name'>
                                        <img src='./assets/icons/product icon.png' alt='product icon' />
                                        <p>$productName</p>
                                        <div class='share-icon-container'>
                                            <a href=''><img src=''./assets/icons/share icon.png' alt='share-icon' /></a>
                                        </div>
                                    </div>
                                    <a href='$productPath'>More Info...</a>
                                </div>
                                <div class='product-info'>
                                    <div class='product-name'>
                                        <img src='./assets/icons/product icon.png' alt='product icon' />
                                        <p>$productName</p>
                                        <div class='share-icon-container'>
                                            <a href='#'><img src='./assets/icons/share icon.png' alt='share-icon' /></a>
                                        </div>
                                    </div>
                                    <div class='product-details'>
                                        <img src='./assets/icons/product info icon.png' alt='product info icon' />
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
                    <img src="./assets/9200093200-LO1-20210425-154728.png" alt="e-commerce image" />
                </div>
                <div class="product-info"style="visibility: hidden;">
                    <div class="product-name">
                        <img src="./assets/icons/product icon.png" alt="product icon" />
                        <p>Built-in Microwave (900 W, 25 L)</p>
                        <div class="share-icon-container">
                            <a href="#"><img src="./assets/icons/share icon.png" alt="share-icon" /></a>
                        </div>
                    </div>
                    <a href="./products/product.html">More Info...</a>
                </div>
                <div class="product-info">
                    <div class="product-name">
                        <img src="./assets/icons/product icon.png" alt="product icon" />
                        <p>Built-in Microwave (900 W, 25 L)</p>
                        <div class="share-icon-container">
                            <a href="#"><img src="./assets/icons/share icon.png" alt="share-icon" /></a>
                        </div>
                    </div>
                    <div class="product-details">
                        <img src="./assets/icons/product info icon.png" alt="product info icon" />
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Iure nisi velit ullam delectus voluptatum laborum quo placeat officia voluptatibus obcaecati. Modi asperiores similique fugiat laborum commodi. Sed sint quod odit!</p>
                    </div>
                    <a href="./products/product.html">More Info...</a>
                </div>
            </div> -->
        </section>
    </main>
    <footer >
        <div class="footer-content-wrap">
            <div class="brands">
                <div class="brand-logo-name">
                    <div class="brand-logo">
                        <img src="./assets/icons/blue beko logo.png" />
                    </div> 
                </div>
                <div class="brands-paragraph-content">
                    <p>Lorem ipsum dolor sit amet consectetur,adipisicing  elit. Necessitatibus sunt maiores velit corporis, ut sed eaque dolores  neque, accusantium  amet accusamus! Debitis aut dolorem nemo expedita accusantium  ipsum dolore iure.</p>
                </div> 
                <div class="social-media-brand-logos">
                    <div>
                        <a href="#instagram">
                            <img src="./assets/icons/instagram icon.png" />
                        </a>
                    </div>
                    <div>
                        <a href="#linkedin">
                            <img src="./assets/icons/linkedin icon.png" />
                        </a>
                    </div>
                    <div>
                        <a href="#facebook">
                            <img src = "./assets/icons/facebook icon.png" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="quick-links">
                <h3>Quick Links</h3>
                <a href="#home">Home</a>
                <a href="#About">About</a>
                <a href="#Shop">Shop</a>
                <a href="#Contact">Contact</a>
            </div>
            <div class="contact">
                <h3>Contact</h3> <br />
                <p>Lorem ipsum dolor sit amet consectetur,  adipisicing elit. Dolorum repellat nihil   voluptatem rerum et, atque porro id fuga voluptas  numquam laboriosam similique. Pariatur itaque neque autem impedit quasi numquam earum?</p>
            </div>
            <div class="news-letter">
                <h3>Subscribe to our Email</h3><br />
                <h4>For Latest News and Updates</h4><br />
                <div class="form-container">
                    <form id="form">
                        <input type="email" id="email" name="email" placeholder="youremail.example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"  required />
                        <input type="Submit" id="subscribe" value="Subscribe"> 
                    </form>
                </div>
            </div>
        </div>
        <div class="copyright-tag">
            <p>Copyright &copy; <span id="year">
            </span> | Beko Corporate | Maintained by <span>
                <a href="https://www.yosambranding.art" target="_blank">Yosam Branding</a>
            </span>
            </p>
        </div>
    </footer>
    
    <script type="text/javascript" src="./scripts/navigation.js"></script>
    <script type="text/javascript" src="./scripts/current-year.js"></script>
</body>
</html>