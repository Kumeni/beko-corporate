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
    <link rel="stylesheet" type="text/css" href="./styles/about-us.css" />
    <title>About Us</title>
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
                        <?php
                            $innerHTML = "";
                            foreach ($categorizedProducts["categories"] as $index => $category) {
                                # code...
                                $categoryId = $category["id"];
                                $categoryName = $category["name"];
                                $innerHTML .= "<a href='./products.php?category-id=$categoryId'>$categoryName</a>";
                            }
                            echo $innerHTML;
                        ?>
                    </ul>
                </div>
            </div>
            <ul class="menu-items">
                <a href="./about-us.php" class="dynamic-text">About Us</a>
                <a class="dynamic-text" href="./contact-us.php" onclick="setFormSubject('RE: General Inquiry', 'Hello, \nHow I would like to inquire about\n')">Contact Us</a>
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
                            $innerHTML .= "<li>
                                <span><a href='./products.php?category-id=$categoryId'>$categoryName</a></span>
                                <div class='underline'></div>
                                <ul>";

                                    foreach ($category["categories"] as $key => $subCategory) {
                                        # code...
                                        $subCategoryId = $subCategory["id"];
                                        $subCategoryName = $subCategory["name"];
                                        $innerHTML .= "<a href='./products.php?category-id=$subCategoryId'>$subCategoryName</a>";
                                    }
                            $innerHTML .="</ul>
                            </li>";
                        }
                        echo $innerHTML;
                    ?>
                </ul>
            </div>
        </div>
    </header>
    <section class="about-us-hero">
        <div class="hero-banner">
            <div class="hero-text-container">
                <h2>About Beko</h2>
            </div>
        </div>
    </section>
    <main class="beko-about-content">
        <section class="beko-about-information">
            <div class="paragrapgh-content">
                <h3>Innovation For Every Home</h3>
                <p>At Beko, we believe in creating appliances that make life easier, healthier and more sustainable for everyone. 
                    Established in 1955, Beko has grown into one of the world's leading home appliance brands, serving millions of 
                    households across over 130 countries. Our mission is to provide high-quality, energy-efficient and innovative products
                    that meet the needs of modern life style, ensuring that every home is equiped with smart, practical solutions.
                </p>
            </div>
            <div class="paragrapgh-content">
                <h3>A Global Leader In Home Appliances</h3>
                <p>As part if Arcelic, one of the largest consumer goods companies, Beko combines decades of expertise with commitment to innovation.
                    we produce a wide range of appliances, from refrigerators, dishwashers and washing machines to cookers, vacuum cleaners, and air 
                    conditioners, all designed with the latest technology to improve  everyday living. Our products are recognized for their durabilty
                    , sleek design, and energy efficiency, making them a preffered choice in homes worldwide.
                </p>
            </div>
            <div class="paragrapgh-content">
                <h3>Our Commitment To Sustainability</h3>
                <p>At Beko, we are committed to building a better future. Sustainability is at the core of everything we do, from the eco-friendly technologies
                    we develop to the materials we use. Our appliances are designed to reduce energy consumption and minimize their environmental impact, 
                    helping consumers live more sustainably without compromising on performance.
                </p>
            </div>
            <div class="paragrapgh-content">
                <h3>Innovation And Smart Technology</h3>
                <p>We constantly push the boundaries of technology to create smarter, more efficient appliances
                    Whether it's smart refrigerators with advanced cooling systems or washing machines
                    that use less water and energy, our goal is to make every task easier and more 
                    convenient. Beko's innovative solutions are inspired by real-life needs, ensuring our customers
                    always get the best in both design and functionality.
                </p>
            </div>
            <div class="paragrapgh-content">
                <h3>Customer-Centric Approach</h3>
                <p>At the heart of Beko is our commitment to our customers. We listen to their needs, adapt to 
                    their evolving preferences, and strive to exceed their expectations. Our goal is to provide appliances
                    that make everyday life simpler, more enjoyable, and worry-free. With a global
                    network of support services and partners, we ensure that help is always at hand when you need it.
                </p>
            </div>
            <div class="paragrapgh-content">
                <h3>Our Vision for the Future</h3>
                <p>Beko envisons a world where technology and sustainability work hand in hand to create healthier
                    smarter, and more sustainable homes. As we look to the future, we will continue to innovate and 
                    lead the industry with solutions that psoitively impact both our customers
                    and the planet.
                </p>
            </div>
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
                <p>Beko is committed to delivering innovative, high-quality appliances designed to make your everyday life easier. With a focus on energy efficiency and smart technology, we strive to bring sustainable solutions to your home.</p>
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
                <a href="./index.html">Home</a>
                 <?php
                    $innerHTML = "";
                    foreach ($categorizedProducts["categories"] as $index => $category) {
                        # code...
                        $categoryId = $category["id"];
                        $categoryName = $category["name"];
                        $innerHTML .= "<a href='./products.php?category-id=$categoryId'>$categoryName</a>";
                    }
                    echo $innerHTML;
                ?>
                <a href="./about-us.php">About Us</a>
                <a href="./contact-us.php">Contact Us</a>
            </div>
            <div class="contact">
                <h3>Contact</h3> <br />
                <div class="contact-information-icons-details">
                    <div class="phone-details">
                        <div class="icon-container">
                            <img src="./assets/icons/phone-white-icon.png" />
                        </div>
                        <div class="phone-link">
                            <a href="tel: +254716785847">+254716785847</a>
                        </div>
                    </div>
                    <div class="email-details">
                        <div class="icon-container">
                            <img src="./assets/icons/email-white-icon.png" />
                        </div>
                        <div class="email-link">
                            <a href="mailto: info@bekocorporatesolutions.com">info@bekocorporatesolutions.com</a>
                        </div>
                    </div>
                    <div class="address-details">
                        <div class="icon-container">
                            <img src="./assets/icons/address-white-icon.png" />
                        </div>
                        <div class="address-link">
                            <p>Home, off gong road</p>
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