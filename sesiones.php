<!DOCTYPE html>
<html>
<head>
    <title>Shopping List</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
        }
        input[type=submit] {
            margin-top: 10px;
        }
    </style>
</head>
<body>



    <h1>Shopping List</h1>

    <form method="post">
        <label for="workerName">Worker name:</label>
        <input type="text" name="name" id="name" required>
        <br>
</form>

    <?php
    session_start();

    

    // Inicializar la lista de productos en la sesión si no existe
    if (!isset($_SESSION['list'])) {
        $_SESSION['list'] = [];
    }

    $error = '';
    $message = '';
    $totalValue = 0;

    // Procesar el formulario cuando se envía
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add'])) {

            // Añadir producto
            $name = $_POST['name'];
            $quantity = (int)$_POST['quantity'];
            $price = (float)$_POST['price'];

            if ($name && $quantity > 0 && $price > 0) {
                $_SESSION['list'][] = [
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price
                ];
                $message = 'Product added';
            } else {
                $error = 'Error';
            }
        } elseif (isset($_POST['Edit'])) {

            // Actualizar un producto existente en la lista
            $index = (int)$_POST['index'];
            $name = $_POST['name'];
            $quantity = (int)$_POST['quantity'];
            $price = (float)$_POST['price'];

            if ($name && $quantity > 0 && $price > 0 && isset($_SESSION['list'][$index])) {
                $_SESSION['list'][$index] = [
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price
                ];
                $message = 'Product updated';
            } else {
                $error = 'Error';
            }
        } elseif (isset($_POST['delete'])) {
            
            // Eliminar un producto de la lista
            $index = (int)$_POST['index'];
            if (isset($_SESSION['list'][$index])) {
                array_splice($_SESSION['list'], $index, 1);
                $message = 'Product deleted';
            }
        } elseif (isset($_POST['reset'])) {
            
            // Reiniciar la lista de productos
            $_SESSION['list'] = [];
            $message = 'Shopping list reseted';
        } elseif (isset($_POST['total'])) {
            
            
            // Calcular el total de la lista
            $totalValue = 0;
            foreach ($_SESSION['list'] as $item) {
                $totalValue += $item['quantity'] * $item['price'];
            }
        }
    }
    ?>

    <form method="post">
    
        <label for="workerName">Worker name:</label>
        <input type="text" name="workerName" id="workerName" required>
        <br>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
        <br>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" min="0.01" required>
        <br>
        <input type="submit" name="add" value="Add">
        <input type="submit" name="reset" value="Reset">
    </form>

    <p style="color:red;"><?php echo $error; ?></p>
    <p style="color:green;"><?php echo $message; ?></p>

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
                        <form method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="submit" name="edit" value="Edit">
                        </form>
                        <form method="post">
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