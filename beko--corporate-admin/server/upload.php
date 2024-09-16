<?php
    //code for uploading a file

    function fileUpload($name, $tmpName){
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        //check if image file is actual image or fake image
        if(isset($_POST["submit"])){
            $check = getimagesize($tmpName);
            if($check !== false){
                //echo "File is an image - ". $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                //echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // $target_dir = "../uploads/" specifies the directory where the file is going to be placed
        // $target_file specifies the path of the file to be uploaded.
        // $uploadOk = 1 is not used yet, will be used later.
        // $imageFileType holds the file extension of the file (in lower case)        

        //check if the file already exists
        if(file_exists($target_file)){
            //echo "Sorry, file already exists.";
            $fileInfo = pathinfo($name);
            $newFileName = $fileInfo["filename"] . "_" . uniqid() . "." . $fileInfo["extension"];
            $name = $newFileName;
            $target_file = $target_dir . $newFileName;
        }

        //check file size
        /*if($_FILES["fileToUPload"]["size"] > 500000){
            echo "Sorry, your file is too large";
            $uplaodOk = 0;
        }*/

        //limit file type
        //Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != 'gif' && $imageFileType != 'avif'){
            //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed";
            $uploadOk = 0;
        }

        //check if $uploadOk is set to 0 by an error
        if($uploadOk == 0){
            //echo "Sorry, your file was not uploaded";
        } else {
            //echo $tmpName;
            if(move_uploaded_file($tmpName, $target_file)){
                #echo "Target file " . $target_file;
                #echo "The file " . htmlspecialchars( basename($name)) . " has been uploaded.";
                return $name;
            } else {
                #echo "Sorry, there was an error uploading your file.";
            }
        }

        return $name;
        //After uploading the files, we should fill in the table with the other values
    }
?>