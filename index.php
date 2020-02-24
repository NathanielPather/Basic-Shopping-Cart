<?php
include("ShoppingCart.php");
session_start();

$products = new Products();
$formatter = new Formatter();
$cart = new Cart($formatter);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Page Title</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>

<h1>Products</h1>
<div id="products-wrapper">
    <ul class="products">
        <?php
        if (isset($_POST['addButton'])) {
            $productId = intVal($_POST['productId']);
            $cart->addToCart($productId);
        }

        echo $products->listProducts();
        ?>
    </ul>
</div>
<h1>Cart</h1>
<div id="cart-wrapper">

    <?php
    if (isset($_POST['removeButton'])) {
        $removeId = intVal($_POST['removeId']);
        $cart->removeFromCart($removeId);
    }

    echo $cart->listItems();
    ?>
</div>

</body>
</html>