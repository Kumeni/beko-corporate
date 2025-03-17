<?php 
    function create($host, $user, $password, $database, $sql){
        //the INSERT INTO statement is used to insert new rows:
        //inserting multiple records into MYSQL using MySQLi Procedural

        $conn = mysqli_connect($host, $user, $password, $database);

        if(!$conn){
            die('Could not connect: '. mysqli_connect_error());
        }

        //echo 'Connected successfully <br/>';
        //$sql = 'INSERT INTO emp4(name, salary) VALUES("sonoo", 9000)';

        if(mysqli_query($conn, $sql)){
            $lastId = mysqli_insert_id($conn);
            //after creating an entry it's prudent to return it.

        } else {
            $lastId = 0;
            //echo "Could not insert record: " . mysqli_error($conn);
        }

        $lastId = mysqli_insert_id($conn);
        
        mysqli_close($conn);
        return  $lastId;
        //This function should return the new entry
    }

    function update($host, $user, $password, $database, $sql){

        // Create connection
        $conn = mysqli_connect($host, $user, $password, $database);

        //Check connection
        if(!$conn){
            die("Connection failed: " . mysqli_connect_error());
        }

        //$sql = "UPDATE MyGuests SET lastname='Doe' WHERE id=2";

        if(mysqli_query($conn, $sql)){
            //echo "Record updated successfully";
        } else {
            //echo "Error updating record: " . mysqli_error($conn);
        }
        $lastId = mysqli_insert_id($conn);

        mysqli_close($conn);
        return  $lastId;
    }

    function find($host, $user, $password, $database, $sql){

        //Create connection
        $conn = mysqli_connect($host, $user, $password, $database);

        //Check connection
        if(!$conn){
            die("Connection failed: " . mysqli_connect_error());
        }

        //$sql = "SELECT id, firstname, lastname FROM MyGuests";
        $result = mysqli_query($conn, $sql);

        /*if(mysqli_num_rows($result) > 0){
            //output data of each row
            while($row = mysqli_fetch_assoc($result)){
                echo "id: ". $row["id"] . " - Name: " .$row["firstname"] . " " . $row["lastname"] . "<br/ >";
            }
        } else {
            echo "0 results";
        }*/

        mysqli_close($conn);
        return $result;
    }
    function getProducts($host, $user, $password, $database, $sql){
        //$sql = "SELECT * FROM products WHERE deleted='0'";
        $products = find($host, $user, $password, $database, $sql);
        $newArray = [];
        foreach ($products as $index => $product) {
            # code...

            #echo "<p> '$index' </p>";
            #$productArray[$index] = "Yooh";
            $newArray[$index] = $product;
        }
        //print_r($newArray);

        $products = $newArray;
        $productWithImagesArray = [];
        foreach ($products as $index1 => $product) {
            # code...
            //fetch the images;
            //update the path to have uploads;
            $productWithImages = $product;
            $newArray = [];
            $sql = "SELECT * FROM images WHERE productId=" . $product["id"]. " AND deleted=0";
            $images = find($host, $user, $password, $database, $sql);
            foreach ($images as $index2 => $image) {
                # code...
                $newArray[$index2] = $image;
            }
            $images = $newArray;

            foreach ($images as $index3 => $image) {
                # code...
                $slashes =  "/". "uploads"."/";
                $images[$index3]["path"] = $slashes . $images[$index3]["path"];
            }
            $productWithImages["images"] = $images;
            $productWithImagesArray[$index1] = $productWithImages;
        }

        $products = $productWithImagesArray;
        return $products;
    }
?>