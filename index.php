<?php

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: signin.php");
    exit;
}

date_default_timezone_set("Asia/Kolkata");

$productData = [];
$orderMessage = "";
$orders = [];

$search = trim($_GET["search"] ?? "");
$brand = trim($_GET["brand"] ?? "");
$sort = trim($_GET["sort"] ?? "");

/* Load Products API */

ob_start();
include "api/list.php";
$response = ob_get_clean();

$data = json_decode($response, true);

if (
    is_array($data) &&
    isset($data["status"]) &&
    $data["status"] === true &&
    isset($data["products"])
) {
    $productData = $data["products"];
}

/* Search */

if ($search != "") {

    $productData = array_filter($productData, function ($item) use ($search) {

        return stripos($item["name"], $search) !== false ||
               stripos($item["brand"], $search) !== false;

    });

}

/* Brand Filter */

if ($brand != "") {

    $productData = array_filter($productData, function ($item) use ($brand) {

        return $item["brand"] == $brand;

    });

}

/* Price Sort */

if ($sort == "low") {

    usort($productData, function ($a, $b) {
        return $a["price"] <=> $b["price"];
    });

}

if ($sort == "high") {

    usort($productData, function ($a, $b) {
        return $b["price"] <=> $a["price"];
    });

}

/* Load Orders */

if (file_exists("orders.json")) {

    $orders = json_decode(file_get_contents("orders.json"), true);

    if (!is_array($orders)) {
        $orders = [];
    }

}

/* Save Order */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["place_order"])) {

    $customerName = trim($_POST["customer_name"]);
    $mobile = trim($_POST["mobile"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $city = trim($_POST["city"]);
    $state = trim($_POST["state"]);
    $pincode = trim($_POST["pincode"]);

    if (
        $customerName == "" ||
        $mobile == "" ||
        $email == "" ||
        $address == "" ||
        $city == "" ||
        $state == "" ||
        $pincode == ""
    ) {

        $orderMessage = "Please fill all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $orderMessage = "Invalid Email Address.";

    } else {

        $orders[] = [

            "customer_name" => htmlspecialchars($customerName),
            "mobile" => htmlspecialchars($mobile),
            "email" => strtolower($email),
            "address" => htmlspecialchars($address),
            "city" => htmlspecialchars($city),
            "state" => htmlspecialchars($state),
            "pincode" => htmlspecialchars($pincode),
            "date" => date("d M Y | h:i A")

        ];

        file_put_contents(
            "orders.json",
            json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $orderMessage = "✅ Order Placed Successfully!";

    }

}

$totalProducts = count($productData);

$totalBrands = count(array_unique(array_column($productData, "brand")));

$totalOrders = count($orders);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Smart Mobile Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<header>

    <h1>Smart Mobile Dashboard</h1>

    <p>
        Welcome,
        <strong><?php echo htmlspecialchars($_SESSION["user"]["name"]); ?></strong>
    </p>

    <a href="logout.php" class="logout-btn">
        Logout
    </a>

</header>

<div class="container">

<!-- Email id: rahulanuofficial@gmail.com -->

    <div class="sale-banner">
        🎉 Mega Sale - Up to 40% OFF on Premium Smartphones
    </div>

    <div class="stats-grid">

        <div class="stats-card">
            <h3>Total Products</h3>
            <p><?php echo $totalProducts; ?></p>
        </div>

        <div class="stats-card">
            <h3>Total Brands</h3>
            <p><?php echo $totalBrands; ?></p>
        </div>

        <div class="stats-card">
            <h3>Total Orders</h3>
            <p><?php echo $totalOrders; ?></p>
        </div>

    </div>

    <div class="card">

        <h2>Search Product</h2>

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search by Product or Brand"
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <select name="brand">

                <option value="">All Brands</option>

                <option value="Samsung" <?php if($brand=="Samsung") echo "selected"; ?>>
                    Samsung
                </option>

                <option value="Oppo" <?php if($brand=="Oppo") echo "selected"; ?>>
                    Oppo
                </option>

                <option value="Vivo" <?php if($brand=="Vivo") echo "selected"; ?>>
                    Vivo
                </option>

                <option value="Realme" <?php if($brand=="Realme") echo "selected"; ?>>
                    Realme
                </option>

                <option value="Redmi" <?php if($brand=="Redmi") echo "selected"; ?>>
                    Redmi
                </option>

                <option value="Apple" <?php if($brand=="Apple") echo "selected"; ?>>
                    Apple
                </option>

            </select>

            <select name="sort">

                <option value="">Sort By Price</option>

                <option value="low" <?php if($sort=="low") echo "selected"; ?>>
                    Low to High
                </option>

                <option value="high" <?php if($sort=="high") echo "selected"; ?>>
                    High to Low
                </option>

            </select>

            <button type="submit">
                Search
            </button>

        </form>

    </div>

    <div class="card">

        <h2>Mobile Products</h2>

        <div class="product-grid">

<?php if (!empty($productData)) { ?>

    <?php foreach ($productData as $item) { ?>

        <div class="product-card">

            <div class="image-box">

                <img
                    src="<?php echo htmlspecialchars($item["image"]); ?>"
                    alt="<?php echo htmlspecialchars($item["name"]); ?>"
                >

            </div>

            <h3>
                <?php echo htmlspecialchars($item["name"]); ?>
            </h3>

            <p>
                <strong>Brand:</strong>
                <?php echo htmlspecialchars($item["brand"]); ?>
            </p>

            <p>
                <strong>RAM:</strong>
                <?php echo htmlspecialchars($item["ram"]); ?>
            </p>

            <p>
                <strong>Storage:</strong>
                <?php echo htmlspecialchars($item["storage"]); ?>
            </p>

            <p>
                <strong>Processor:</strong>
                <?php echo htmlspecialchars($item["processor"]); ?>
            </p>

            <p>
                <strong>Camera:</strong>
                <?php echo htmlspecialchars($item["camera"]); ?>
            </p>

            <p>
                <strong>Battery:</strong>
                <?php echo htmlspecialchars($item["battery"]); ?>
            </p>

            <p>
                <strong>Display:</strong>
                <?php echo htmlspecialchars($item["display"]); ?>
            </p>

            <p>
                <strong>Rating:</strong>
                ⭐ <?php echo htmlspecialchars($item["rating"]); ?>
            </p>

            <p>
                <strong>Stock:</strong>
                <?php echo htmlspecialchars($item["stock"]); ?>
            </p>

            <p>
                <strong>Discount:</strong>
                <?php echo htmlspecialchars($item["discount"]); ?>
            </p>

            <div class="price">
                ₹<?php echo number_format($item["price"]); ?>
            </div>

        </div>

    <?php } ?>

<?php } else { ?>

    <div class="not-found">

        <h2>No Product Found</h2>

        <p>
            Try searching with another product or brand.
        </p>

    </div>

<?php } ?>

        </div>

    </div>

<div class="card">

    <h2>Place Order</h2>

    <form method="POST">

        <input
            type="text"
            name="customer_name"
            placeholder="Full Name"
            required
        >

        <input
            type="text"
            name="mobile"
            placeholder="Mobile Number"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        >

        <input
            type="text"
            name="address"
            placeholder="Address"
            required
        >

        <input
            type="text"
            name="city"
            placeholder="City"
            required
        >

        <input
            type="text"
            name="state"
            placeholder="State"
            required
        >

        <input
            type="text"
            name="pincode"
            placeholder="Pincode"
            required
        >

        <button type="submit" name="place_order">
            Place Order
        </button>

    </form>

    <?php if ($orderMessage != "") { ?>

        <div class="success-box">
            <?php echo htmlspecialchars($orderMessage); ?>
        </div>

    <?php } ?>

</div>

<div class="card">

    <h2>Order History</h2>

    <?php if (!empty($orders)) { ?>

        <?php foreach (array_reverse($orders) as $order) { ?>

            <div class="product-card">

                <p><strong>Name:</strong> <?php echo htmlspecialchars($order["customer_name"]); ?></p>
                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($order["mobile"]); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order["email"]); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order["address"]); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($order["city"]); ?></p>
                <p><strong>State:</strong> <?php echo htmlspecialchars($order["state"]); ?></p>
                <p><strong>Pincode:</strong> <?php echo htmlspecialchars($order["pincode"]); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($order["date"]); ?></p>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="not-found">
            <h2>No Orders Yet</h2>
            <p>Place your first order now.</p>
        </div>

    <?php } ?>

</div>

</div>

<footer>

    <p>© 2026 Smart Mobile Dashboard</p>

    <p>All Rights Reserved | Created by Rahul Kumar</p>

</footer>

</body>
</html>