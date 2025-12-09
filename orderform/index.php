<?php
session_start();

// Initialize receipt items in session if not already
if(!isset($_SESSION['receiptItems'])) {
    $_SESSION['receiptItems'] = [];
}

// Handle form submission to add item
if(isset($_POST['addItem'])) {
    $drink = $_POST['drink'];
    $size = $_POST['size'];
    $qty = (int)$_POST['qty'];
    $topping = $_POST['topping'];

    // Prices
    $drinkPrices = [
        "Coke Float" => 35,
        "Sprite Float" => 35,
        "Royal Float" => 35,
        "Iced Coffee Float" => 45
    ];
    $sizePrices = [
        "Regular" => 0,
        "Large" => 10,
        "X-Large" => 20
    ];
    $toppingPrices = [
        "None" => 0,
        "Chocolate Syrup" => 10,
        "Caramel Drizzle" => 10,
        "Extra Ice Cream" => 15
    ];

    $itemTotal = ($drinkPrices[$drink] + $sizePrices[$size] + $toppingPrices[$topping]) * $qty;

    $_SESSION['receiptItems'][] = [
        'drink' => $drink,
        'size' => $size,
        'topping' => $topping,
        'qty' => $qty,
        'itemTotal' => $itemTotal
    ];
}

// Handle removal of item
if(isset($_GET['remove'])) {
    $index = (int)$_GET['remove'];
    if(isset($_SESSION['receiptItems'][$index])) {
        array_splice($_SESSION['receiptItems'], $index, 1);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coke Float Order Form</title>
<style>
    body { font-family: Poppins, sans-serif; background: #f0f6ff; padding: 20px; }
    h1 { text-align: center; color: #1b3c8d; }
    .card { background: #fff; padding: 20px; margin: 15px auto; max-width: 500px; border-radius: 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    label { font-weight: 600; }
    select, input { width: 100%; padding: 10px; margin: 8px 0 16px; border-radius: 10px; border: 1px solid #ccc; }
    button { width: 100%; padding: 14px; background: #1a2a6d; color: white; border: none; border-radius: 15px; font-size: 18px; cursor: pointer; margin-top: 8px; }
    button:hover { background: #152257; }
    #receipt { margin-top: 20px; padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    #receipt h2 { text-align: center; color: #1b3c8d; }
    #receipt ul { padding-left: 20px; }
    .remove-btn { background: #e11d48; margin-left: 10px; padding: 2px 6px; font-size: 12px; border-radius: 6px; cursor: pointer; text-decoration: none; color: white; }
</style>
</head>
<body>

<h1>🍨 Float Order Form</h1>

<div class="card">
    <form method="post">
        <label>Choose Your Drink</label>
        <select name="drink" required>
            <option value="Coke Float">Coke Float – ₱35</option>
            <option value="Sprite Float">Sprite Float – ₱35</option>
            <option value="Royal Float">Royal Float – ₱35</option>
            <option value="Iced Coffee Float">Iced Coffee Float – ₱45</option>
        </select>

        <label>Size</label>
        <select name="size" required>
            <option value="Regular">Regular</option>
            <option value="Large">Large (+₱10)</option>
            <option value="X-Large">X-Large (+₱20)</option>
        </select>

        <label>Quantity</label>
        <input type="number" name="qty" min="1" value="1" required>

        <label>Toppings</label>
        <select name="topping" required>
            <option value="None">None</option>
            <option value="Chocolate Syrup">Chocolate Syrup (+₱10)</option>
            <option value="Caramel Drizzle">Caramel Drizzle (+₱10)</option>
            <option value="Extra Ice Cream">Extra Ice Cream (+₱15)</option>
        </select>

        <button type="submit" name="addItem">Add to Receipt</button>
    </form>
</div>

<div class="card" id="receipt">
    <h2>🧾 Live Order Receipt</h2>
    <?php if(empty($_SESSION['receiptItems'])): ?>
        <p>No items yet.</p>
    <?php else: ?>
        <ul>
            <?php 
            $subtotal = 0;
            foreach($_SESSION['receiptItems'] as $index => $item): 
                $subtotal += $item['itemTotal'];
            ?>
            <li>
                <?= $item['qty'] ?> x <?= $item['drink'] ?> (<?= $item['size'] ?>, Topping: <?= $item['topping'] ?>) = ₱<?= number_format($item['itemTotal'], 2) ?>
                <a class="remove-btn" href="?remove=<?= $index ?>">Remove</a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php 
        $tax = $subtotal * 0.05;
        $total = $subtotal + $tax;
        ?>
        <p><strong>Subtotal:</strong> ₱<?= number_format($subtotal,2) ?></p>
        <p><strong>Tax (5%):</strong> ₱<?= number_format($tax,2) ?></p>
        <p><strong>Total:</strong> ₱<?= number_format($total,2) ?></p>
    <?php endif; ?>
</div>

</body>
</html>
