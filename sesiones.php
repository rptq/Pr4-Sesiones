<?php
session_start();

if (!isset($_SESSION['list'])) {
    $_SESSION['list'] = [];
}

$error = '';
$message = '';
$totalValue = 0;
$editIndex = null;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $quantity = (int)$_POST['quantity'];
        $price = (float)$_POST['price'];

        if ($name && $quantity > 0 && $price > 0) {
            $_SESSION['list'][] = ['name' => $name, 'quantity' => $quantity, 'price' => $price];
            $message = 'Product added';
        } else {
            $error = 'Please enter valid data.';
        }

    } elseif (isset($_POST['edit'])) {
        $editIndex = (int)$_POST['index'];

    } elseif (isset($_POST['update'])) {
        $index = (int)$_POST['index'];
        $name = $_POST['name'];
        $quantity = (int)$_POST['quantity'];
        $price = (float)$_POST['price'];

        if ($name && $quantity > 0 && $price > 0 && isset($_SESSION['list'][$index])) {
            $_SESSION['list'][$index] = ['name' => $name, 'quantity' => $quantity, 'price' => $price];
            $message = 'Product updated';
        } else {
            $error = 'Invalid update data.';
        }

    } elseif (isset($_POST['delete'])) {
        $index = (int)$_POST['index'];
        if (isset($_SESSION['list'][$index])) {
            array_splice($_SESSION['list'], $index, 1);
            $message = 'Product deleted';
        }

    } elseif (isset($_POST['reset'])) {
        $_SESSION['list'] = [];
        $message = 'List reset';

    } elseif (isset($_POST['total'])) {
        foreach ($_SESSION['list'] as $item) {
            $totalValue += $item['quantity'] * $item['price'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping List</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; }
        th, td { padding: 5px; }
        input[type=submit] { margin-top: 10px; }
    </style>
</head>
<body>

<h1>Shopping List</h1>

<p style="color:red;"><?php echo $error; ?></p>
<p style="color:green;"><?php echo $message; ?></p>

<?php if ($editIndex !== null): ?>
    <!-- Formulario de edición -->
    <form method="post">
        <input type="hidden" name="index" value="<?php echo $editIndex; ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['list'][$editIndex]['name']); ?>" required><br>
        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?php echo $_SESSION['list'][$editIndex]['quantity']; ?>" min="1" required><br>
        <label>Price:</label>
        <input type="number" name="price" value="<?php echo $_SESSION['list'][$editIndex]['price']; ?>" step="0.01" min="0.01" required><br>
        <input type="submit" name="update" value="Update">
    </form>
<?php else: ?>
    <!-- Formulario de añadir -->
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Quantity:</label>
        <input type="number" name="quantity" min="1" required><br>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" min="0.01" required><br>
        <input type="submit" name="add" value="Add">
        <input type="submit" name="reset" value="Reset">
    </form>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Cost</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($_SESSION['list'] as $index => $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <input type="submit" name="edit" value="Edit">
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <input type="submit" name="delete" value="Delete">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3" align="right"><strong>Total:</strong></td>
        <td><?php echo number_format($totalValue, 2); ?></td>
        <td>
            <form method="post">
                <input type="submit" name="total" value="Calculate Total">
            </form>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
