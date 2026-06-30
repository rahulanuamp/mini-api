<?php

header("Content-Type: application/json");

try {

    $products = [

        [
            "id" => 1,
            "name" => "Samsung Galaxy A14 5G",
            "brand" => "Samsung",
            "price" => 20990,
            "ram" => "8GB",
            "storage" => "128GB",
            "processor" => "Exynos 850",
            "camera" => "50MP",
            "battery" => "5000mAh",
            "display" => "6.6 inch FHD+",
            "rating" => "4.5",
            "discount" => "15% OFF",
            "stock" => "In Stock",
            "image" => "../assets/product1.jpg"
        ],

        [
            "id" => 2,
            "name" => "Oppo K14x 5G",
            "brand" => "Oppo",
            "price" => 16999,
            "ram" => "8GB",
            "storage" => "128GB",
            "processor" => "Snapdragon",
            "camera" => "64MP",
            "battery" => "5000mAh",
            "display" => "6.7 inch AMOLED",
            "rating" => "4.4",
            "discount" => "12% OFF",
            "stock" => "In Stock",
            "image" => "../assets/product2.jpg"
        ],

        [
            "id" => 3,
            "name" => "Vivo V23 5G",
            "brand" => "Vivo",
            "price" => 34990,
            "ram" => "12GB",
            "storage" => "256GB",
            "processor" => "Dimensity",
            "camera" => "64MP",
            "battery" => "4500mAh",
            "display" => "6.44 inch AMOLED",
            "rating" => "4.6",
            "discount" => "10% OFF",
            "stock" => "In Stock",
            "image" => "../assets/product3.jpg"
        ],

        [
            "id" => 4,
            "name" => "Realme P4x 5G",
            "brand" => "Realme",
            "price" => 19499,
            "ram" => "8GB",
            "storage" => "128GB",
            "processor" => "Snapdragon",
            "camera" => "50MP",
            "battery" => "5000mAh",
            "display" => "6.5 inch IPS",
            "rating" => "4.3",
            "discount" => "8% OFF",
            "stock" => "Limited Stock",
            "image" => "../assets/product4.jpg"
        ],

        [
            "id" => 5,
            "name" => "Redmi Note 14 SE",
            "brand" => "Redmi",
            "price" => 21990,
            "ram" => "8GB",
            "storage" => "256GB",
            "processor" => "MediaTek",
            "camera" => "108MP",
            "battery" => "5100mAh",
            "display" => "6.67 inch AMOLED",
            "rating" => "4.7",
            "discount" => "18% OFF",
            "stock" => "In Stock",
            "image" => "../assets/product5.jpg"
        ],

        [
            "id" => 6,
            "name" => "Apple iPhone 17",
            "brand" => "Apple",
            "price" => 77900,
            "ram" => "8GB",
            "storage" => "256GB",
            "processor" => "A18 Bionic",
            "camera" => "48MP",
            "battery" => "4200mAh",
            "display" => "6.1 inch Super Retina",
            "rating" => "4.9",
            "discount" => "5% OFF",
            "stock" => "In Stock",
            "image" => "../assets/product6.jpg"
        ]

    ];

    echo json_encode(
        [
            "status" => true,
            "total_products" => count($products),
            "products" => $products
        ],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

} catch (Exception $e) {

    echo json_encode(
        [
            "status" => false,
            "message" => $e->getMessage()
        ]
    );

}

?>