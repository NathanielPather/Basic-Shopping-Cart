<?php
error_reporting(E_STRICT);
session_start();

class Cart
{
    private $formatter;
    private $products;

    public function __construct($formatter, $products)
    {
        $_SESSION["Cart"] = $this->getCart();
        $this->formatter = $formatter;
        $this->products = $products;
    }

    /*
    ** Get the current cart items.
    ** Current cart items are provided from $_SESSION["Cart"]
    ** If the $_SESSION["Cart"] is empty create an empty cart
    **
    ** Returns a $cart
     */
    function getCart()
    {
        if ($_SESSION["Cart"] == "") {
            return $cart = [
                ["id" => 0, "price" => 125.75, "quantity" => 0],
                ["id" => 1, "price" => 190.50, "quantity" => 0],
                ["id" => 2, "price" => 562.131, "quantity" => 0],
                ["id" => 3, "price" => 12.9, "quantity" => 0],
                ["id" => 4, "price" => 18.45, "quantity" => 0],
            ];
        } else {
            return $_SESSION["Cart"];
        }
    }

    /*
    ** Set the current cart.
    **
    ** Input is a $cart.
    ** Returns a $cart $_Session["Cart"]
     */
    function setCart($cart)
    {
        return $_SESSION["Cart"] = $cart;
    }

    /*
    ** Adds items to a cart.
    **
    ** Input is $productId
    ** Returns a function call to setCart().
     */
    function addToCart($productId)
    {
        $cart = $this->getCart();
        $cart[$productId]["quantity"]++;

        return $this->setCart($cart);
    }

    /*
    ** Gets the product name.
    **
    ** Input is an $item.
    ** Returns a String for the item name.
     */
    function getName($item)
    {
        $productList = $this->products->getProducts();

        return $productList[$item["id"]]["name"];
    }

    /*
    ** Gets total cost for a cart item.
    **
    ** Input is an $item.
    ** Returns a function call to formatTotal();
     */
    function getTotal($item)
    {
        $total = $item["quantity"] * $item["price"];

        return $this->formatter->formatTotal($total);
    }

    /*
    ** Gets the overall total cost for the cart.
    **
    ** Returns a function call to formatTotal();
     */
    function getOverallTotal()
    {
        $total = 0;
        for ($i = 0; $i < count($_SESSION["Cart"]); $i++) {
            if ($_SESSION["Cart"][$i]["quantity"] > 0) {
                $total += $_SESSION["Cart"][$i]["quantity"] * $_SESSION["Cart"][$i]["price"];
            }
        }

        return $this->formatter->formatTotal($total);
    }

    /*
    ** Lists the items in the cart.
    **
    ** Returns a $html substring.
     */
    function listItems()
    {
        $html = "";
        $id = 0;
        foreach ($_SESSION["Cart"] as $item) {
            if ($item["quantity"] > 0) {
                $html = $html .
                    '<pre>
                        <div class="name">' . $this->getName($item) . '</div><div class="price">Price: $' . $this->formatter->formatPrice($item) . '</div><div class="quantity">Quantity: ' . $item["quantity"] . '</div><div class="total">Total: $' . $this->getTotal($item) . '</div><form class="removeForm" method="POST"><input class="removeInput" type="hidden" name="removeId" value=' . $id . ' /><input class="removeInput"  type="submit" name="removeButton" value="Remove from Cart" /></form>
                    </pre>';
            }
            $id++;
        }

        return $this->outputTotal($html);
    }

    function outputTotal($html)
    {
        $html = $html .
            '<pre>
                <div class="overallTotal">Overall Total: $' . $this->getOverallTotal() . '</div>
            </pre>';

        return $html;
    }

    /*
    ** Removes items from a cart.
    **
    ** Input is $productId.
    ** Returns a function call to setCart().
     */
    function removeFromCart($removeId)
    {
        $cart = $this->getCart();
        $cart[$removeId]["quantity"]--;

        return $this->setCart($cart);

    }
}

class Products
{
    private $products;

    public function __construct()
    {
        $this->products = $this->getProducts();
    }

    /*
    ** Get products data.
    **
    ** Returns an array of arrays for product items.
     */
    function getProducts()
    {
        return
            // ######## please do not alter the following code ########
            $products = [
                ["name" => "Sledgehammer", "price" => 125.75],
                ["name" => "Axe", "price" => 190.50],
                ["name" => "Bandsaw", "price" => 562.131],
                ["name" => "Chisel", "price" => 12.9],
                ["name" => "Hacksaw", "price" => 18.45],
            ];
        // ########################################################
    }

    /*
    ** Lists the products to buy.
    **
    ** Returns a $html substring.
     */
    public function listProducts()
    {
        $html = "";
        $id = 0;
        $formatter = new Formatter();
        foreach ($this->products as $product) {
            $html = $html .
                '<li>
                    <div class="name">' . $product["name"] . '</div><div class="price">$' . $formatter->formatPrice($product) . '</div>
                    <form class="addForm" method="POST">
                        <input class="addInput"  type="hidden" name="productId" value=' . $id . ' />
                        <input class="addInput"  type="submit" name="addButton" value="Add to Cart" />
                    </form>
                </li>';
            $id++;
        }

        return $html;
    }
}

class Formatter
{

    /*
    ** Format the price of an item.
    **
    ** Input is an $item.
    ** Returns a formatted number.
     */
    function formatPrice($item)
    {
        $number = $item["price"];

        return number_format($number, 2);
    }

    /*
    ** Format the total price of a number.
    **
    ** Input is a $number.
    ** Returns a formatted number.
     */
    function formatTotal($number)
    {
        return number_format($number, 2);
    }
}