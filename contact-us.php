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
    <link rel="stylesheet" type="text/css" href="./styles/global.css" />
    <link rel="stylesheet" type="text/css" href="./styles/header.css" />
    <link rel="stylesheet" type="text/css" href="./styles/footer.css" />
    <link rel="stylesheet" type="text/css" href="./styles/contact-us-hero.css" />
    <link rel="stylesheet" type="text/css" href="./styles/contactUs-getInTouch.css" />

    <?php 
        echo "<script>let categorizedProducts=$categorizedProductsJSON; console.log(categorizedProducts);</script>";
    ?>
    <title>Contact Us</title>
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
                                        $innerHTML .= "<a href='https://www.beko.com/ke-en'>$categoryName</a>";
                                    } else {
                                        $innerHTML .= "<a href='./products.php?category-id=$categoryId'>$categoryName</a>";
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
                                $innerHTML .= "<span><a href='https://www.beko.com/ke-en'>$categoryName</a></span>";
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
    <section class="contact-us-hero">
        <div class="hero-banner">
            <div class="hero-text-container">
                <p>At Beko, we're committed to providing exceptional customer service and ensuring your experience with our products is nothing short of excellent. Whether you have a question, need support, or want to give feedback, our team is here to assist you. Please feel free to reach out using the options below, and we'll get back to you as soon as possible.</p>
            </div>
        </div>
    </section>
    <main class="contact-us-channels">
        <section class="get-in-touch">
            <div class="get-in-touch-container">
                <!-- <div class="get-in-touch-header">
                    <p class="breadcrumbs"> <a href="/">Home</a> / <a href="/products.html">Products</a></p>
                </div> -->
                <div class="contact-information-container">
                    <div class="contact-information">
                        <div class="contact-information-text-container">
                            <div class="contact-info-header">
                                <h2>Get in Touch with Beko</h2>
                            </div>
                            <div class="contact-info-paragraph">
                                <p>We're here to help! Whether you prefer reaching us by phone, email, or mail, you’ll find all the details you need below. Our team is ready to assist with any questions or support you may need. Don't hesitate to contact us—we're just a message away!</p>
                            </div>
                        </div>
                        <div class="contact-information-icons-details">
                            <div class="phone-details">
                                <div class="icon-container">
                                    <img src="./assets/icons/phone-white-icon.png" />
                                </div>
                                <div class="phone-link">
                                    <p>Phone</p>
                                    <a href="tel:+254768444404">+254 768 444 404</a>
                                </div>
                            </div>
                            <div class="email-details">
                                <div class="icon-container">
                                    <img src="./assets/icons/email-white-icon.png" />
                                </div>
                                <div class="email-link">
                                    <p>Email</p>
                                    <a href="mailto:bekocorporate@Koch.co.ke">bekocorporate@koch.co.ke</a>
                                </div>
                            </div>
                            <div class="address-details">
                                <div class="icon-container">
                                    <img src="./assets/icons/address-white-icon.png" />
                                </div>
                                <div class="address-link">
                                    <p>Address</p>
                                    <p>
                                        Ground Floor, Apollo Center <br />
                                        Ring Road,  Nairobi – Kenya.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div></div>
                        <div class="social-media-icons">
                            <div class="social-header">
                                <h4>Follow Us:</h4>
                            </div>
                            <div class="icon-container">
                                <a href="https://www.instagram.com/bekocorporate_solutions/" target="_blank">
                                    <img src="./assets/icons/instagram icon.png" />
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-form">
                        <div class="contact-form-details">
                            <h4>Send Us a Message</h4>
                            <div class="underline"></div>
                            <form>
                                <input type="email" id="email" name="email" placeholder="Email" required/>
                                <textarea type="text" name="message" id="message" placeholder="Message..." required></textarea>
                                <input type="submit" value="SEND US A MESSAGE" />
                            </form>
                        </div>
                    </div>
                </div>
                <!-- <div class="gle-maps">
                    <div class="gle-maps-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.777225041228!2d36.77961597496577!3d-1.3089279986786446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f11616b6fff21%3A0x47f83d7f26050db!2sCustom%20t-shirt%20branding!5e0!3m2!1sen!2ske!4v1725734306670!5m2!1sen!2ske" width=300 height=500 style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div> -->
            </div>
        </section>
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
                <a href="./index.html">Home</a>
                 <?php
                    $innerHTML = "";
                    foreach ($categorizedProducts["categories"] as $index => $category) {
                        # code...
                        $categoryId = $category["id"];
                        $categoryName = $category["name"];
                        if($categoryId == 1){
                            $innerHTML .= "<a href='https://www.beko.com/ke-en'>$categoryName</a>";
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
            </span> | Beko Corporate | Maintained by <span>
                <a href="https://www.yosambranding.art" target="_blank">Yosam Branding</a>
            </span>
            </p>
        </div>
    </footer>

    <script type="text/javascript" src="./scripts/navigation.js"></script>
    <script type="text/javascript" src="./scripts/footer.js"></script>
    <script type="text/javascript" src="./scripts/current-year.js"></script>
</body>
</html>