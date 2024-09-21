<?php 

    require './db.php';
    require './db-operations.php';
    require './upload.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(isset($_POST["deleted"])){
            /**
             * Delete the vehicle and return
             */
            $id = $_POST["id"];
            $sql = "UPDATE transport-vehicles SET deleted='1' WHERE id=$id";
            update($host, $user, $password, $database, $sql);
            echo json_encode(getTransportVehicles($host, $user, $password, $database));
            die();
        }

        if(isset($_POST["deleted-images"])){
            /**
             * Delete all the deleted images
             */
            $deletedImages = $_POST["deleted-images"];
            $deletedImages = json_decode($deletedImages);

            foreach ($deletedImages as $index => $deletedImage) {
                # code...
                $sql = "UPDATE images SET deleted='1' WHERE id=$deletedImage";
                update($host, $user, $password, $database, $sql);
            }
        }

        if(isset($_POST["product-name"])){
            $productName = $_POST["product-name"];
            $productName = test_input($productName);
        }

        if(isset($_POST["product-description"])){
            $productDescription = $_POST["product-description"];
            $productDescription = test_input($productDescription);
        }
        
        if(isset($_POST["storage-width"])){
            $storageWidth = $_POST["storage-width"];
            $storageWidth = test_input($storageWidth);
        }

        if(isset($_POST["varieties"])){
            $varieties = $_POST["varieties"];
            //$varieties = test_input($varieties);
        }

        if(isset($_POST["deleted-varieties"])){
            $deletedVarieties = $_POST["deleted-varieties"];
            //$varieties = test_input($varieties);
        }
        
        if(isset($_POST["specifications"])){
            $specifications = $_POST["specifications"];
            //$specifications = test_input($specifications);
        }
        
        if(isset($_POST["categories"])){
            $categories = $_POST["categories"];
            //$categories = test_input($categories);
        }
        
        if(isset($_POST["articles"])){
            $articles = $_POST["articles"];
            //$articles = test_input($articles);
        }
        
        if(isset($_POST["articles-deltas"])){
            $articlesDeltas = $_POST["articles-deltas"];
            //$articles = test_input($articles);
        }

        //$sql = "INSERT INTO events(`name`, `description`, `host`, `start_datetime`, `end_datetime`, `venue`, `google_maps_direction`, `vvip`, `available_vvip_tickets`, `vip`, `available_vip_tickets`,`regular`, `available_regular_tickets`, `deleted`) VALUES('$name', '$description', '$host_name', '$start_datetime', '$end_datetime', '$venue', '$google_maps_direction','$vvip', '$available_vvip_tickets', '$vip', '$available_vip_tickets', '$regular', '$available_regular_tickets', '$deleted')";
        //$sql = "UPDATE events SET tracker_id='$selectedTracker'";

        /**
         * Create the product || If product id exists update the product
         */
        if(isset($_POST["id"])){
            /**
             * Edit the product
             */
            $productId = $_POST["id"];
            $sql = "UPDATE products SET name='$productName', description='$productDescription' WHERE id=$productId";
            update($host, $user, $password, $database, $sql);
        } else {
            /**
             * Create the product;
             */
            $sql = "INSERT INTO products(`name`, `description`) VALUES('$productName', '$productDescription')";
            $productId = create($host, $user, $password, $database, $sql);
        }

        /**
         * Create varieties
         */
        $varieties = json_decode($varieties, true);
        foreach ($varieties as $key => $variety) {
            # code...
            if(isset($variety["id"])){
                /**
                 * Update existing variety
                 */
                $varietyId = $variety["id"];
                $varietyName = $variety["name"];
                $varietyDescription = $variety["description"];
                $varietyPrice = $variety["price"];

                $sql = "UPDATE product_varieties SET name='$varietyName', price='$varietyPrice', description='$varietyDescription' WHERE id=$varietyId";
                update($host, $user, $password, $database, $sql);
                $varieties[$key]["variety-id"] = $varietyId;
            } else {
                /**
                 * Create an entry for each variety and store the ids
                 */
                $varietyName = $variety["name"];
                $varietyDescription = $variety["description"];
                $varietyPrice = $variety["price"];

                $sql = "INSERT INTO product_varieties(`name`, `price`, `description`, `product_id`) VALUES('$varietyName', '$varietyPrice', '$varietyDescription', '$productId')";
                $varieties[$key]["variety-id"] = create($host, $user, $password, $database, $sql);
            }
            
        }

        /**
         * Delete a deleted variety
         */
        if(isset($deletedVarieties)){
            $deletedVarieties = json_decode($deletedVarieties, true);
            foreach ($deletedVarieties as $key => $deletedVarietyId) {
                # code...
                
                $sql = "UPDATE product_varieties SET deleted='1' WHERE id=$deletedVarietyId";
                update($host, $user, $password, $database, $sql);
            }
        }
        

        /**
         * Upload the images here
         */
        // Loop through each file in $_FILES
        foreach ($_FILES as $key => $file) {
            // Example key format: image_0_0 (image_row_column)
            if (preg_match('/image_(\d+)_(\d+)/', $key, $matches)) {
                $row = $matches[1]; // Row index indicating the variety
                $col = $matches[2]; // Column index indicating image in a variety
                
                // Check if there was no error during the file upload
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $file['tmp_name'];
                    $filename = basename($file['name']);
                    
                    if(!empty($tmpName) && is_uploaded_file($tmpName)){
                        // Get image size
                        $image_info = getimagesize($tmpName);

                        $targetFile = fileUpload($filename,  $tmpName);
    
                        $width = $image_info[0];  // Image width
                        $height = $image_info[1]; // Image height
                        $varietyId = $varieties[$row]["variety-id"];

                        $sql = "INSERT INTO images(`variety_id`, `width`, `height`, `path`) VALUES('$varietyId', '$width', '$height', '$targetFile')";
                        create($host, $user, $password, $database, $sql);
    
                        //$eventImages[$index] = array("eventId"=>$eventId, "path"=>$targetFile);
                    }
                } else {
                    $errors[] = "Error uploading file {$fileName}.";
                }
            }
        }


        /**
         * Create product specifications and
         * product specifications key value pairs
         */
        $specifications = json_decode($specifications, true);
        foreach ($specifications as $key => $specification) {
            /**
             * Create an entry for each specification
             */
            $groupName = $specification["groupName"];
            if(isset($specification["id"])){
                /**
                 * If specification exists, update it
                 */
                $productSpecificationsId = $specification["id"];
                $sql = "UPDATE product_specifications SET group_name='$groupName' WHERE id=$productSpecificationsId";
                update($host, $user, $password, $database, $sql);
            } else {
                $sql = "INSERT INTO product_specifications(`group_name`, `product_id`) VALUES('$groupName', '$productId')";
                $productSpecificationsId = create($host, $user, $password, $database, $sql);
            }
            

            foreach ($specification["propertiesAndValues"] as $key2=> $propertyAndValue) {
                /**
                 * Create a related property and value;
                 */
                $property = $propertyAndValue["property"];
                $value = $propertyAndValue["value"];
                if(isset($propertyAndValue["id"])){
                    /**
                     * Update existing property and value
                     */
                    $propertyAndValueId = $propertyAndValue["id"];
                    $sql = "UPDATE product_specifications_key_value_pairs SET property='$property', value='$value' WHERE id=$propertyAndValueId";
                    update($host, $user, $password, $database, $sql);
                } else {
                    $sql = "INSERT INTO product_specifications_key_value_pairs(`property`, `value`, `product_specifications_id`) VALUES('$property', '$value', '$productSpecificationsId')";
                    create($host, $user, $password, $database, $sql);
                }
            }
        }

        /**
         * Delete Property and values
         */
        if(isset($_POST["deleted-property-and-values"])){
            $deletedPropertyAndValues = $_POST["deleted-property-and-values"];
            $deletedPropertyAndValues = json_decode($deletedPropertyAndValues, true);
            foreach ($deletedPropertyAndValues as $key => $deletedPropertyAndValueId) {
                # code...
                $sql = "UPDATE product_specifications_key_value_pairs SET deleted='1' WHERE id=$deletedPropertyAndValueId";
                update($host, $user, $password, $database, $sql);
            }
        }

        /**
         * Create product categories relations
         */
        $categories = json_decode($categories, true);

        /**
         * Fetch all the categories belonging to this product and delete them;
         */
        $sql = "SELECT * FROM product_categories_relations WHERE deleted='0' AND product_id='$productId'";
        $productCategoryRelations = find($host, $user, $password, $database, $sql);

        $newArray = [];

        foreach ($productCategoryRelations as $key2 => $productCategoryRelation) {
            # code...
            //$newArray[] = $productCategoryRelation;
            $productCategoryRelationId = $productCategoryRelation["id"];
            $sql = "UPDATE product_categories_relations SET deleted='1' WHERE id=$productCategoryRelationId";
            update($host, $user, $password, $database, $sql);
        }

        foreach ($categories as $key => $category) {
            /**
             * Create product categories
             */
            $categoryId = $category["id"];
            $sql = "INSERT INTO product_categories_relations(`category_id`, `product_id`) VALUES('$categoryId', '$productId')";
            create($host, $user, $password, $database, $sql);
        }

        /**
         * Create product articles;
         */
        //var_dump($articles);
        $articles = json_decode($articles, true);
        //var_dump($articlesDeltas);

        foreach ($articles as $key => $article) {
            /**
             * Create articles in the database;
             */
            $html = $article["html"];
            $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
            $html= htmlspecialchars($html, ENT_QUOTES);
            $text = $article["text"];
            //$delta = $articlesDeltas[$key]; 
            if(isset($article["id"])){
                /**
                 * Update the existing article
                 */
                $articleId = $article["id"];
                $delta = $articlesDeltas[$key];

                $delta = html_entity_decode($delta, ENT_QUOTES, 'UTF-8');
                $delta = addslashes($delta);
                $text = addslashes($text);

                //$sql = "UPDATE articles SET html='$html', text='$text' WHERE id=$articleId";
                $sql = "UPDATE articles SET delta='$delta', html = '$html', text = '$text' WHERE id=$articleId";
                update($host, $user, $password, $database, $sql);
            } else {
                $delta = $articlesDeltas[$key];
                $delta = html_entity_decode($delta, ENT_QUOTES, 'UTF-8');

                $delta = addslashes($delta);
                $text = addslashes($text);
                $sql = "INSERT INTO articles(`delta`, `html`, `text`, `product_id`) VALUES('$delta', '$html', '$text', '$productId')";
                create($host, $user, $password, $database, $sql);
            }
        }

        /**
         * Delete a deleted variety
         */
        if(isset($_POST["deleted-articles"])){
            $deletedArticles = $_POST["deleted-articles"];
            $deletedArticles = json_decode($deletedArticles, true);
            foreach ($deletedArticles as $key => $deletedArticleId) {
                # code...
                
                $sql = "UPDATE articles SET deleted='1' WHERE id=$deletedArticleId";
                update($host, $user, $password, $database, $sql);
            }
        }

        /*if(isset($_FILES["vehicleImages"])){
            //upload the image to the server;
            //upload the image to the server;
            
            foreach ($_FILES["vehicleImages"]["name"] as $index => $vehicleImage) {
                # code...

                $tmpName = $_FILES["vehicleImages"]["tmp_name"][$index];

                if(!empty( $_FILES["vehicleImages"]["error"][$index])){
                    //some error occured with the file in index $index
                    die();
                }

                if(!empty($tmpName) && is_uploaded_file($tmpName)){
                    //do something with it
                    $filename = $_FILES["vehicleImages"]["name"][$index];
                    $tmpName = $_FILES["vehicleImages"]["tmp_name"][$index];

                    $targetFile = fileUpload($filename,  $tmpName);

                    //var_dump($targetFile);
                    $sql = "INSERT INTO images(`transport_vehicle_id`, `path`) VALUES('$id', '$targetFile')";
                    create($host, $user, $password, $database, $sql);

                    //$eventImages[$index] = array("eventId"=>$eventId, "path"=>$targetFile);
                }
            }
        }*/

        echo json_encode(getAllProducts($host, $user, $password, $database));
    } if($_SERVER["REQUEST_METHOD"] == "GET"){
        echo json_encode(getAllProducts($host, $user, $password, $database));
    }

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
                unset($article["html"]);
                
                //var_dump($article["delta"]);
                //$article["html"] = stripslashes($article["html"]);
                //$article["html"] = htmlspecialchars_decode($article["html"]);
                //$article["text"] = stripslashes($article["text"]);
                //$article["text"] = addslashes($article["text"]);
                $article["delta"] = json_decode($article["delta"], true);

                //var_dump(json_encode($article["delta"]));
                //unset($article["delta"]);
                $article["text"] = addslashes($article["text"]);
                //var_dump($article["text"]);
                $newArray[] = $article;
            }

            $articles = $newArray;
            $products[$index]["articles"] = $articles;
        }

        //return $products;
        $products = json_encode($products);
        return $products;
    }

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
?>