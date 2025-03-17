<?php 

    require './db.php';
    require './db-operations.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        /**
         * Creating a new category
         */

        

        $category = $_POST["category"];
        $category = test_input($category);
        
        $parent = $_POST["parent"];
        $parent = test_input($parent);

        if(isset($_POST["id"])){
            /**
             * Editing an existing category;
             */
            $id = $_POST["id"];
            $id = test_input($id);

            $sql = "UPDATE product_categories SET name='$category' WHERE id=$id";
            update($host, $user, $password, $database, $sql);

            echo availableProductCategories($host, $user, $password, $database);
            die();
        }

        $sql = "INSERT INTO product_categories(`name`, `parent`) VALUES('$category', '$parent')";
        create($host, $user, $password, $database, $sql);

        echo availableProductCategories($host, $user, $password, $database);
        die();

    } else if($_SERVER["REQUEST_METHOD"] == "GET"){
        echo availableProductCategories($host, $user, $password, $database);
        die();
    } else if($_SERVER["REQUEST_METHOD"] == "PUT"){

        $category = $_PUT["category"];
        $category = test_input($category);
        
        $parent = $_PUT["parent"];
        $parent = test_input($parent);

        $id = $_PUT["id"];
        $id = test_input($id);


        $sql = "UPDATE product_categories SET category='$category' WHERE id=$id";
        update($host, $user, $password, $database, $sql);

        echo availableProductCategories($host, $user, $password, $database);
    } else if ($_SERVER["REQUEST_METHOD"] == "DELETE"){

        $data = json_decode(file_get_contents("php://input"), true);
        //parse_str(file_get_contents("php://input"), $data);

        $id = $data["id"];
        $id = test_input($id);
        
        //handle deleting categories
        $sql = "UPDATE product_categories SET deleted='1' WHERE id=$id";
        update($host, $user, $password, $database, $sql);

        echo availableProductCategories($host, $user, $password, $database);
        die();
    }

    /**
     * Fetches the available sensor categories from the database and returns a json response;
     */
    function availableProductCategories($host, $user, $password, $database){
        
        //ini_set('memory_limit', '512M'); // Increase memory limit
        $sql = "SELECT * FROM product_categories WHERE deleted='0'";
        $productCategories = find($host, $user, $password, $database, $sql);
        $newArray = [];
        foreach ($productCategories as $index => $productCategory) {
            # code...
            $newArray[$index] = $productCategory;
        }

        $productCategories = $newArray;
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
        $productCategories = buildCategoryTree($productCategories);
        $root = [
            "id" => 0,
            "categories" => $productCategories
        ];
        return json_encode($root);
    }

    // Function to build a tree structure from categories
    function buildCategoryTree($categories, $parentId = 0) {
        $tree = [];
        
        foreach ($categories as $category) {
            if ($category['parent'] == $parentId) {
                $children = buildCategoryTree($categories, $category['id']);
                if ($children) {
                    $category['categories'] = $children;
                }
                $tree[] = $category;
            }
        }
        
        return $tree;
    }
    /**
     * Generate categories tree
     */
    /*function generateCategoriesTree($rootElement, $categories){
        $rootElement["categories"] = [];
        foreach($categories as $index => $category){
            if($category["parent"] == $rootElement["id"]){
                $category["categories"] = [];
                $rootElement["categories"][] = $category;
                $lastIndex = array_key_last($rootElement["categories"]);
                generateCategoriesTree($rootElement["categories"][$lastIndex], $categories);
            }
        }
        return $rootElement;
    }*/
    
    /*function generateFullCategoriesTree($categories){
        
        $root = [
            "id" => 0,
            "categories" => []
        ];
        
        function generateCategoriesTree($rootElement, $categories){
            var_dump($root);
            $rootElement["categories"] = [];
            foreach($categories as $index => $category){
                if($category["parent"] == $rootElement["id"]){
                    $category["categories"] = [];
                    $rootElement["categories"][] = $category;
                    //var_dump($category);
                    //$lastIndex = array_key_last($rootElement["categories"]);
                    //generateCategoriesTree($rootElement["categories"][$lastIndex], $categories);
                }
            }

            foreach ($rootElement["categories"] as $index => $category2) {
                # code...
                generateCategoriesTree($rootElement["categories"][$index], $categories);
            }
            
            //return $rootElement;
            //var_dump($rootElement);
        }

        generateCategoriesTree($root, $categories);
        var_dump($root);
        //var_dump(generateCategoriesTree($root, $categories));
        //return json_encode();
    }*/
    
    /**
     * This function ensures we're not getting hacked, at least :))
     */
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
   
?>