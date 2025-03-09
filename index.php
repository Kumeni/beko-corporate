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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./scripts/swiper/swiper-bundle.min.css" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="./styles/global.css" />
    <link rel="stylesheet" type="text/css" href="./styles/footer.css" />
    <link rel="stylesheet" type="text/css" href="./styles/header.css" />
    <link rel="stylesheet" type="text/css" href="./styles/homepage-hero.css" />
    <link rel="stylesheet" type="text/css" href="./styles/homepage-main.css" />
    <link rel="stylesheet" type="text/css" href="./styles/homepage-landing.css" />
    <link rel="stylesheet" type="text/css" href="./styles/what-we-offer.css" />

    <?php 
        echo "<script>let categorizedProducts=$categorizedProductsJSON; console.log(categorizedProducts);</script>";
    ?>

    <title>Beko Corporate Solutions</title>
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
                <a href="./"><img class="beko-logo" src="./assets/icons/Beko Corporate Solutions Logo BLUE.png" alt="Beko logo white" /></a>
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
                                                $innerHTML .= "<a href='./products.php?category-id=$categoryId'>$categoryName</a>";
                                            }

                                            $innerHTML .= "</ul>";
                                        } else {
                                            $innerHTML .= "<a href='./products.php?category-id=$categoryId'>$categoryName</a>";
                                        }
                                    }
                                }
                                echo $innerHTML;
                            ?>
                        </ul>
                    </div>
                </div>
                <a href="./about-us.php" class="dynamic-text">About Us</a>
                <a class="dynamic-text" href="./contact-us.php" onclick="setFormSubject('RE: General Inquiry', 'Hello, \nI would like to inquire about\n')">Contact Us</a>
            </ul>
            <div
                class="menu-bars small-screen-menu-bars" 
                id="navigation-bars" 
                onclick="toggleNavigation(true)">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="hamburger-menu">
            <div id="hamburger-menu-container">
                <ul class="hamburger-menu-ul">
                    <span title="Close" class="close-hamburger-menu" onclick="toggleNavigation(false)">&times;</span>
                    <a href="./">Home</a>
                    <a href="./about-us.php">About Us</a>
                    <a href="./contact-us.php" onclick="setFormSubject('RE: General Inquiry', 'Hello, \nHow I would like to inquire about\n')">Contact Us</a>
                    <?php
                        $innerHTML = "";
                        foreach ($categorizedProducts["categories"] as $index => $category) {
                            # code...
                            $categoryId = $category["id"];
                            $categoryName = $category["name"];
                            $innerHTML .= "<li>";
                            if($categoryId == 1){
                                $innerHTML .= "<span><a href='https://www.beko.com/ke-en' target='blank'>$categoryName</a></span>";
                            } else {
                                $innerHTML .= "<span><a href='./products.php?category-id=$categoryId'>$categoryName</a></span>";
                            }
                            
                            $innerHTML .="
                                <div class='underline'></div>
                                <ul>";
                                    if($categoryId != 1){
                                        foreach ($category["categories"] as $key => $subCategory) {
                                            # code...
                                            $subCategoryId = $subCategory["id"];
                                            $subCategoryName = $subCategory["name"];
                                            $innerHTML .= "<a href='./products.php?category-id=$subCategoryId'>$subCategoryName</a>";
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
    <!-- <section class="homepage-landing-container" style="position:sticky"></section>
        <section class="homepage-landing-decoy"></section>
    </section> -->
    <section class="homepage-landing-container">
        <section class="homepage-landing">
            <div class="navigation-buttons">
                <button class="left-arrow" onclick="prevLandingSlide()"><img src="./assets/icons/left arrow.png" alt="left arrow" /></button>
                <button class="right-arrow" onclick="nextLandingSlide()"><img src="./assets/icons/right arrow.png" alt="right arrow" /></button>
            </div>
            <div class="swiper-wrapper">
                    <!-- Slides -->
                    <!-- <div class="swiper-slide small landing_logo">
                        <img id="landing_logo"src="./icons/solar prime logo.png" alt = "solar prime logo" />
                    </div> -->
                    <div class="swiper-slide slide-1">
                        <section class="landing">
                            <div class="landing-image">
                                <!-- <img src="./assets/homepage-images/landing-images/beko no 1 banner.jpg" alt="office layout by beko corporate" /> -->
                                <!-- <img src="./assets/homepage-images/landing-images/banners5.jpg" alt="office layout by beko corporate" /> -->
                                <img class="small-screen-banner" src="./assets/homepage-images/landing-images/beko no 1 banner small.jpg" />
                                <img class="large-screen-banner" src="./assets/homepage-images/landing-images/beko no 1 banner.jpg" alt="office layout by beko corporate" />
                                
                            <!-- <div class="landing-image">
                                <img src="./assets/homepage-images/landing-images/office layout by beko corporate.png" alt="office layout by beko corporate" />
                            </div>
                            <div class="blue-rectangle-container">
                                <div class="blue-rectangle fly-in-from-right"></div>
                            </div>
                            <div class="beko-logo-container">
                                <img class="beko-logo-landing fly-in-from-right" src="./assets/icons/white beko logo.png" alt="beko logo" />
                            </div>
                            <div class="slide-title">
                                <h1 class="index fly-in-from-right">01</h1>
                                <h1 class="title fly-in-from-right">Commited to<br /> Sustainability</h1>
                            </div>
                            <div class="more-info-container">
                                <div class="more-info fly-in-from-right">
                                    <p class="fly-in-from-right">We are dedicated to supporting your sustainability goals. Our energy-efficient appliances not only reduce operational costs but also contribute to a greener planet.</p>
                                </div>
                            </div> -->
                        </section>
                    </div>
                    <!-- <div class="swiper-slide slide-2">
                        <section class="landing">
                            <div class="landing-image">
                                <img class="small-screen-banner" src="./assets/homepage-images/landing-images/beko kitchen appliances banner small.jpg" alt="fully furnished kitchen by beko corporate" />
                                <img class="large-screen-banner" src="./assets/homepage-images/landing-images/beko kitchen appliances banner.jpg" alt="fully furnished kitchen by beko corporate" />
                            </div>
                            <div class="landing-image">
                                <img src="./assets/homepage-images/landing-images/fully furnished kitchen by beko corporate.png" alt="fully furnished kitchen by beko corporate" />
                            </div>
                            <div class="blue-rectangle-container">
                                <div class="blue-rectangle fly-in-from-right"></div>
                            </div>
                            <div class="beko-logo-container">
                                <img class="beko-logo-landing fly-in-from-right" src="./assets/icons/white beko logo.png" alt="beko logo" />
                            </div>
                            <div class="slide-title">
                                <h1 class="index fly-in-from-right">02</h1>
                                <h1 class="title fly-in-from-right">Tailored Solutions for<br />Every Industry</h1>
                            </div>
                            <div class="more-info-container">
                                <div class="more-info fly-in-from-right">
                                    <p class="fly-in-from-right">Beko’s in-built appliances are crafted with cutting-edge technology and rigorous quality standards. From state-of-the-art kitchen appliances to advanced laundry solutions, we provide products that deliver consistent results and stand the test of time.</p>
                                </div>
                            </div>
                        </section>
                    </div> -->
                    <div class="swiper-slide slide-3">
                        <section class="landing">
                            <div class="landing-image">
                                <img class="small-screen-banner" src="./assets/homepage-images/landing-images/beko productions networks banner small.jpg" alt="office with huge windows by beko corporate" />
                                <img class="large-screen-banner" src="./assets/homepage-images/landing-images/beko productions networks banner.jpg" alt="office with huge windows by beko corporate" />
                            </div>
                            <!-- <div class="landing-image">
                                <img src="./assets/homepage-images/landing-images/office with huge windows by beko corporate.png" alt="office with huge windows by beko corporate" />
                            </div>
                            <div class="blue-rectangle-container">
                                <div class="blue-rectangle fly-in-from-right"></div>
                            </div>
                            <div class="beko-logo-container">
                                <img class="beko-logo-landing fly-in-from-right" src="./assets/icons/white beko logo.png" alt="beko logo" />
                            </div>
                            <div class="slide-title ">
                                <h1 class="index fly-in-from-right">03</h1>
                                <h1 class="title fly-in-from-right">Innovative Technology,<br />Unmatched Quality</h1>
                            </div>
                            <div class="more-info-container">
                                <div class="more-info fly-in-from-right">
                                    <p class="fly-in-from-right">Beko’s in-built appliances are crafted with cutting-edge technology and rigorous quality standards. From state-of-the-art kitchen appliances to advanced laundry solutions, we provide products that deliver consistent results and stand the test of time.</p>
                                </div>
                            </div> -->
                        </section>
                    </div>
                    <div class="swiper-slide slide-3">
                        <section class="landing">
                            <div class="landing-image">
                                <img class="small-screen-banner" src="./assets/homepage-images/landing-images/beko employees banner small.jpg" alt="office with huge windows by beko corporate" />
                                <img class="large-screen-banner" src="./assets/homepage-images/landing-images/beko employees banner.jpg" alt="office with huge windows by beko corporate" />
                            </div>
                            <!-- <div class="landing-image">
                                <img src="./assets/homepage-images/landing-images/office with huge windows by beko corporate.png" alt="office with huge windows by beko corporate" />
                            </div>
                            <div class="blue-rectangle-container">
                                <div class="blue-rectangle fly-in-from-right"></div>
                            </div>
                            <div class="beko-logo-container">
                                <img class="beko-logo-landing fly-in-from-right" src="./assets/icons/white beko logo.png" alt="beko logo" />
                            </div>
                            <div class="slide-title ">
                                <h1 class="index fly-in-from-right">03</h1>
                                <h1 class="title fly-in-from-right">Innovative Technology,<br />Unmatched Quality</h1>
                            </div>
                            <div class="more-info-container">
                                <div class="more-info fly-in-from-right">
                                    <p class="fly-in-from-right">Beko’s in-built appliances are crafted with cutting-edge technology and rigorous quality standards. From state-of-the-art kitchen appliances to advanced laundry solutions, we provide products that deliver consistent results and stand the test of time.</p>
                                </div>
                            </div> -->
                        </section>
                    </div>
                    <div class="swiper-slide slide-3">
                        <section class="landing">
                            <div class="landing-image">
                                <img class="small-screen-banner" src="./assets/homepage-images/landing-images/Built-in-Appliances-small.jpg" alt="office with huge windows by beko corporate" />
                                <img class="large-screen-banner" src="./assets/homepage-images/landing-images/Built-in-Appliances.jpg" alt="Built in Home Appliances" />
                            </div>
                            <!-- <div class="landing-image">
                                <img src="./assets/homepage-images/landing-images/office with huge windows by beko corporate.png" alt="office with huge windows by beko corporate" />
                            </div>
                            <div class="blue-rectangle-container">
                                <div class="blue-rectangle fly-in-from-right"></div>
                            </div>
                            <div class="beko-logo-container">
                                <img class="beko-logo-landing fly-in-from-right" src="./assets/icons/white beko logo.png" alt="beko logo" />
                            </div>
                            <div class="slide-title ">
                                <h1 class="index fly-in-from-right">03</h1>
                                <h1 class="title fly-in-from-right">Innovative Technology,<br />Unmatched Quality</h1>
                            </div>
                            <div class="more-info-container">
                                <div class="more-info fly-in-from-right">
                                    <p class="fly-in-from-right">Beko’s in-built appliances are crafted with cutting-edge technology and rigorous quality standards. From state-of-the-art kitchen appliances to advanced laundry solutions, we provide products that deliver consistent results and stand the test of time.</p>
                                </div>
                            </div> -->
                        </section>
                    </div>
                </div>
        </section>
    </section>
    
    <main>
        <section class="homepage-hero">
            <div class="hero-container-wrapper">
                <!-- <div class="at-beko">
                    <div class="hero-image-container">
                        <img src="./assets/homepage-images/kitchen appliances 3 by beko.png" />
                    </div>
                    <div class="hero-text-container">
                        <h2>At Beko</h2>
                        <div class="underline"></div>
                        <p>We embrace sustainability as our business model and aim to inspire sustainable lives throughout our value chain.</p>
                    </div>
                </div> -->
                <div class="welcome-to-beko">
                    <div class="hero-text-container">
                        <h2>Welcome to Beko Corporate Solutions</h2>
                        <div class="underline"></div>
                        <p>At Beko Corporate Solutions, we understand that in today’s fast-paced business environment, your organization needs more than just appliances—it needs reliable, efficient, and innovative solutions that drive productivity and enhance your operational efficiency.</p>
                    </div>
                    <div class="hero-image-container">
                        <!-- <img src="./assets/homepage-images/kitchen appliances 2 by beko.png" /> -->
                    </div>
                </div>
            </div>
        </section>
        <section class="what-we-offer">
            <h2>What We Offer</h2>
            <div class="underline"></div>
            <div class="offers">
                <div class="offer home-appliances">
                    <div class="offer-image-container">
                        <!-- <img src="./assets/homepage-images/Beko Europes no 1 banner.png" alt="Beko Europes no 1 banner" /> -->
                        <video autoplay muted loop>
                            <source src="./assets/homepage-images/No clutter here, just culinary delight! .mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <h3 style="visibility: hidden;">Beko Built In Home Appliances</h3>
                    <a href="https://www.beko.com/ke-en" target="_blank" class="browse-button">Browse...</a>
                </div>
                <div class="offer solar-panel-products">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/residential solar panels.png" alt="Solar Panels" />
                    </div>
                    <h3>Solar Solutions</h3>
                    <a href="./products.php?category-id=2" class="browse-button">Browse...</a>
                </div>
                <div class="offer ac-solutions">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/arcelic vrf systems.png" alt="AC Solutions" />
                    </div>
                    <h3>AC Solutions</h3>
                    <a href="./products.php?category-id=3" class="browse-button">Browse...</a>
                </div>
                <div class="offer hotel-concepts">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/Hotel Concepts.png" alt="Hotel Concept" />
                    </div>
                    <div>
                        <h3>Regina Solutions</h3>
                        <ul>
                            <li>Matresses</li>
                            <li>Beds</li>
                            <li>Hotel Textiles</li>
                        </ul>
                    </div>
                    <a href="./products.php?category-id=4" class="browse-button">Browse...</a>
                </div>
                <div class="offer kitchen-cabinets">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/Kitchen cabinets.png" alt="Kitchen Cabinets" />
                    </div>
                    <div>
                        <h3>Cullinan Solutions</h3>
                        <ul>
                            <li>Kitchen Joinery</li>
                            <li>Wardrobe</li>
                            <li>Accessories</li>
                            <li>Doors</li>
                            <li>Wall Cladding</li>
                        </ul>
                    </div>
                    <a href="./products.php?category-id=5" class="browse-button">Browse...</a>
                </div>
                <!-- <div class="offer wardrobes">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/Wardrobes.png" alt="Wardrobes" />
                    </div>
                    <h3>Wardrobes</h3>
                    <a href="./products.php?category-id=6" class="browse-button">Browse...</a>
                </div>
                <div class="offer doors">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/Doors.png" alt="Doors" />
                    </div>
                    <h3>Doors</h3>
                    <a href="./products.php?category-id=7" class="browse-button">Browse...</a>
                </div> -->
                <div class="offer corporate-scenting-solutions">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/Corporate Scenting Solutions.png" alt="Corporate Scenting Solution" />
                    </div>
                    <h3>Corporate Scenting Solutions</h3>
                    <a href="./products.php?category-id=8" class="browse-button">Browse...</a>
                </div>
                <div class="offer ev-chargers">
                    <div class="offer-image-container">
                        <img src="./assets/homepage-images/EV Chargers.png" alt="EV Charger" />
                    </div>
                    <h3>EV Chargers Solutions</h3>
                    <a href="./products.php?category-id=9" class="browse-button">Browse...</a>
                </div>
                <div class="partner-with-bekocorporate">
                    <!-- <div class="hero-image-container">
                        <img src="./assets/homepage-images/kitchen appliances by beko.png" />
                    </div> -->
                    <div class="partner-text-container">
                        <h4>Partner With Beko Corporate Solutions</h4>
                        <div class="underline"></div>
                        <p>Empower your business with innovative, reliable, and sustainable appliance solutions. Partner with Beko Corporate Solutions and take your operations to the next level.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="why-partner-with-bekocorporate">
            <div class="partnering-with-bekocorporate">
                <!-- <div class="partner-with-bekocorporate">
                    <div class="hero-image-container">
                        <img src="./assets/homepage-images/kitchen appliances by beko.png" />
                    </div>
                    <div class="partner-text-container">
                        <h4>Partner With Beko Corporate Solutions</h4>
                        <div class="underline"></div>
                        <p>Empower your business with innovative, reliable, and sustainable appliance solutions. Partner with Beko Corporate Solutions and take your operations to the next level.</p>
                    </div>
                </div> -->
                <div class="why-bekocorporate">
                    <div class="my-heading">
                        <h3>Why Beko Corporate Solutions</h3>
                        <div class="underline"></div>
                    </div>
                    <div class="content-wrapper">
                        <div class="list-style-icon">
                            <img src="./assets/icons/tick icon.png" />
                        </div>
                        <div class="text-container">
                            <div class="header">
                                <h4>Industry Expertise</h4>
                            </div>
                            <p>With years of experience, we understand the unique challenges of various industries and provide solutions that are tailored to meet them.</p>
                        </div>
                    </div>
                    <div class="content-wrapper">
                        <div class="list-style-icon">
                            <img src="./assets/icons/tick icon.png" />
                        </div>
                        <div class="text-container">
                            <div class="header">
                                <h4>Comprehensive Support</h4>
                            </div>
                            <p>From consultation to installation and maintenance, we offer end-to-end support to ensure your operations run smoothly.</p>
                        </div>
                    </div>
                    <div class="content-wrapper">
                        <div class="list-style-icon">
                            <img src="./assets/icons/tick icon.png" />
                        </div>
                        <div class="text-container">
                            <div class="header">
                                <h4>Global Presence, Local Understanding</h4>
                            </div>
                            <p>As a global brand with a deep understanding of local markets, we provide solutions that are both globally competitive and locally relevant.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- <section class="beko-production-network">
            <div class="beko-production-network">
                <div class="beko-production-network-image">
                    <div class="beko-in-numbers-image">
                        <img src="./assets/homepage-images/Beko Production Network.png" />
                    </div>
                    <div class="beko-in-numbers">
                        <img src="./assets/homepage-images/Beko In Numbers.png" />
                    </div>
                </div>
            </div>
        </section> -->

    </main>
    
    <footer >
        <div class="footer-content-wrap">
            <div class="brands">
                <div class="brand-logo-name">
                    <div class="brand-logo">
                        <img src="./assets/icons/Beko Corporate Solutions Logo BLUE.png" alt="Beko Corporate Solutions Blue Logo" />
                    </div> 
                </div>
                <div class="brands-paragraph-content">
                    <p>We work for a sustainable future through our technology, human resources, and production power.
                    Our vision is to rejuvenate ourselves and our industry to become a trusted lifestyle solutions provider to the digital household.</p>
                </div> 
                <div class="social-media-brand-logos">
                    <div>
                        <a href="https://www.instagram.com/bekocorporate_solutions/" target="_blank">
                            <img src="./assets/icons/instagram icon.png" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="quick-links">
                <h3>Quick Links</h3>
                <a href="./index.php">Home</a>
                 <?php
                    $innerHTML = "";
                    foreach ($categorizedProducts["categories"] as $index => $category) {
                        # code...
                        $categoryId = $category["id"];
                        $categoryName = $category["name"];
                        if($categoryId == 1){
                            $innerHTML .= "<a href='https://www.beko.com/ke-en' target='_blank'>$categoryName</a>";
                        } else {
                            $innerHTML .= "<a href='./products.php?category-id=$categoryId'>$categoryName</a>";
                        }
                        
                    }
                    echo $innerHTML;
                ?>
                <a href="./about-us.php">About Us</a>
                <a href="./contact-us.php">Contact Us</a>
            </div>
            <div class="contact">
                <h3>Contacts</h3> <br />
                <div class="contact-information-icons-details">
                    <div class="phone-details">
                        <div class="icon-container">
                            <img src="./assets/icons/phone-white-icon.png" />
                        </div>
                        <div class="phone-link">
                            <a href="tel:+254768444404">+254 768 444 404</a>
                        </div>
                    </div>
                    <div class="email-details">
                        <div class="icon-container">
                            <img src="./assets/icons/email-white-icon.png" />
                        </div>
                        <div class="email-link">
                            <a href="mailto:bekocorporate@Koch.co.ke">bekocorporate@koch.co.ke</a>
                        </div>
                    </div>
                    <div class="address-details">
                        <div class="icon-container">
                            <img src="./assets/icons/address-white-icon.png" />
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
    
    
    <script type="text/javascript" src="./scripts/navigation.js"></script>
    <script type="text/javascript" src="./scripts/footer.js"></script>
    <script type="text/javascript" src="./scripts/share-product.js"></script>
    <script type="text/javascript" src = "./scripts/swiper/swiper-bundle.min.js"></script>
    <script type="text/javascript" src = "./scripts/swiper/initialize-swiper-multiple.js"></script>
    <script type="text/javascript" src="./scripts/swiper/initialize-swiper.js"></script>
    <script type="text/javascript" src = "./scripts/landing-animation.js"></script>
    <script type="text/javascript" src="./scripts/current-year.js"></script>
</body>
</html>