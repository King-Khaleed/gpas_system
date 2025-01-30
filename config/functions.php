<?php 


function countAllProducts(){
    global $conn;
    // Get total number of products
    $total_products_query = "SELECT COUNT(*) AS total_products FROM products";
    $total_products_result = $conn->query($total_products_query);
    $total_products = $total_products_result->fetch_assoc();
    $total_product_counts = $total_products['total_products'];
    return $total_product_counts;
}

function sumAllProductPrice(){
    global $conn;
    $total_price_query = "SELECT SUM(product_price) AS total_price FROM products";
    $total_price_result = $conn->query($total_price_query);
    $total_price = $total_price_result->fetch_assoc()['total_price'] ?? 0;
    return $total_price;
}