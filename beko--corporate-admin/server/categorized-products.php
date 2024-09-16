<?php

    require './db.php';
    require './db-operations.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        /**
         * Return the categorized products
         */
        echo getCategorizedProducts($host, $user, $password, $database);
    } else if($_SERVER["REQUEST_METHOD"] == "GET"){
        echo getCategorizedProducts($host, $user, $password, $database);
    } 

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
        return json_encode($root);
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

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    
// Sample categories and products data
/*$categories = [
    ['id' => 1, 'name' => 'Electronics', 'parent' => 0],
    ['id' => 2, 'name' => 'Phones', 'parent' => 1],
    ['id' => 3, 'name' => 'Laptops', 'parent' => 1],
    ['id' => 4, 'name' => 'Accessories', 'parent' => 1]
];

$products = [
    ['name' => 'iPhone', 'category' => 2],
    ['name' => 'Samsung Galaxy', 'category' => 2],
    ['name' => 'MacBook Pro', 'category' => 3],
    ['name' => 'Laptop Bag', 'category' => 4]
];

// Build the category tree with products
$categoryTree = buildCategoryTreeWithProducts($categories, $products);

// Output the result
print_r($categoryTree);*/
?>
