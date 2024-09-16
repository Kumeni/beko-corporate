<?php

// Directory to save uploaded files
$uploadDir = 'uploads/';
$errors = [];
$images2DArray = []; // 2D array to store file paths

// Ensure the upload directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Loop through each file in $_FILES
foreach ($_FILES as $key => $file) {
    // Example key format: image_0_0 (image_row_column)
    if (preg_match('/image_(\d+)_(\d+)/', $key, $matches)) {
        $row = $matches[1]; // Row index
        $col = $matches[2]; // Column index
        
        // Check if there was no error during the file upload
        if ($file['error'] === UPLOAD_ERR_OK) {
            $tmpName = $file['tmp_name'];
            $fileName = basename($file['name']);
            $destination = $uploadDir . $fileName;
            
            // Move the uploaded file to the destination directory
            if (move_uploaded_file($tmpName, $destination)) {
                // Place the uploaded file in the 2D array
                if (!isset($images2DArray[$row])) {
                    $images2DArray[$row] = [];
                }
                $images2DArray[$row][$col] = $destination;
            } else {
                $errors[] = "Failed to upload file {$fileName}.";
            }
        } else {
            $errors[] = "Error uploading file {$fileName}.";
        }
    }
}

// Output the result
if (!empty($errors)) {
    // Output errors if any
    echo "Errors occurred during the upload:<br>";
    echo implode('<br>', $errors);
} else {
    // Output the reconstructed 2D array of file paths
    echo "<h3>Uploaded Images (2D Array):</h3>";
    echo "<pre>";
    print_r($images2DArray);
    echo "</pre>";
}

?>
