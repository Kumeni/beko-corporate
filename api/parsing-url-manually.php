<?php
// Assuming URL is www.domain.com/category-name/product-name
$requestUri = $_SERVER['REQUEST_URI']; // e.g., /category-name/product-name

// Remove leading slash and split the URL into parts
$urlParts = explode('/', trim($requestUri, '/'));

// Check if we have both category and product name
if (count($urlParts) >= 2) {
    $category = $urlParts[0]; // First part is the category name
    $product = $urlParts[1];  // Second part is the product name
    
    // Now you can use these variables dynamically
    echo "Category: " . htmlspecialchars($category) . "<br>";
    echo "Product: " . htmlspecialchars($product) . "<br>";
} else {
    echo "Invalid URL format.";
}
?>
