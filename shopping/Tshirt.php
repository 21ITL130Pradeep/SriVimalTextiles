<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $designName = $_FILES['design']['name'];
    $designTmpName = $_FILES['design']['tmp_name'];
    $designSize = $_POST['size'];
    $designQty = $_POST['qty'];
    $designPrice = $_POST['price'];

    // Upload directory
    $uploadDir = "uploads/";

    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFile = $uploadDir . basename($designName);

    if (move_uploaded_file($designTmpName, $uploadFile)) {
        // File uploaded successfully
        $message = "Design uploaded successfully!";
    } else {
        $message = "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-commerce Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: relative;
        }

        nav {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 10px 0;
        }

        nav a {
            margin: 0 15px;
            color: white;
            text-decoration: none;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #555;
        }

        #logo {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            height: 70px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        form {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 0 auto; /* Center the form */
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="file"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            background-color: #28a745;
        }

        .product-image {
            max-width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <header>
        <img id="logo" src="C:\xampp\htdocs\vimals\shopping\assets\images\vimalogo.PNG" alt="Logo">
        <h2>Select Product</h2>
    </header>

    <nav>
        <a href="#" onclick="showTshirtForm()">T-Shirt Printing</a>
        <a href="#" onclick="showHoodieForm()">Hoodie Printing</a>
    </nav>
<center>
    <div id="tshirtForm" style="display:none;">
        <h3>T-Shirt Printing</h3>
        <img src="https://5.imimg.com/data5/SELLER/Default/2020/10/XL/ZQ/JY/92819111/t-shirt-printing.png" alt="T-Shirt" class="product-image" height=350>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="design">Upload Design:</label>
            <input type="file" name="design" required><br>

            <label for="size">Select Size:</label>
            <select name="size" onchange="updatePrice('tshirtSize', 'tshirtPrice')">
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select><br>

            <label for="qty">Quantity:</label>
            <div>
                <button type="button" onclick="decreaseQuantity('tshirtQty')">-</button>
                <input type="number" name="qty" id="tshirtQty" value="1" min="1" onchange="updatePrice('tshirtSize', 'tshirtPrice')">
                <button type="button" onclick="increaseQuantity('tshirtQty')">+</button>
            </div>

            <label for="price">Total Amount (in Rupees):</label>
            <input type="number" name="price" id="tshirtPrice" value="250" min="0" readonly><br>

            <button type="submit">Submit</button>
        </form>
    </div>

    <div id="hoodieForm" style="display:none;">
        <h3>Hoodie Printing</h3>
        <img src="https://digiprintshop.com/wp-content/uploads/2021/12/ssh20d000-r1.jpg" alt="Hoodie" class="product-image">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="design">Upload Design:</label>
            <input type="file" name="design" required><br>

            <label for="size">Select Size:</label>
            <select name="size" onchange="updatePrice('hoodieSize', 'hoodiePrice')">
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select><br>

            <label for="qty">Quantity:</label>
            <div>
                <button type="button" onclick="decreaseQuantity('hoodieQty')">-</button>
                <input type="number" name="qty" id="hoodieQty" value="1" min="1" onchange="updatePrice('hoodieSize', 'hoodiePrice')">
                <button type="button" onclick="increaseQuantity('hoodieQty')">+</button>
            </div>

            <label for="price">Total Amount (in Rupees):</label>
            <input type="number" name="price" id="hoodiePrice" value="700" min="0" readonly><br>

            <button type="submit">Submit</button>
        </form>
    </div>

    <div>
        <?php
        if (isset($message)) {
            echo $message;
        }
        ?>
    </div>

    <script>
        function showTshirtForm() {
            document.getElementById("tshirtForm").style.display = "block";
            document.getElementById("hoodieForm").style.display = "none";
        }

        function showHoodieForm() {
            document.getElementById("hoodieForm").style.display = "block";
            document.getElementById("tshirtForm").style.display = "none";
        }

        function increaseQuantity(inputId) {
            document.getElementById(inputId).value = parseInt(document.getElementById(inputId).value) + 1;
            updatePrice(inputId.replace('Qty', 'Size'), inputId.replace('Qty', 'Price'));
        }

        function decreaseQuantity(inputId) {
            if (parseInt(document.getElementById(inputId).value) > 1) {
                document.getElementById(inputId).value = parseInt(document.getElementById(inputId).value) - 1;
                updatePrice(inputId.replace('Qty', 'Size'), inputId.replace('Qty', 'Price'));
            }
        }

        function updatePrice(sizeId, priceId) {
            var size = document.getElementsByName(sizeId)[0].value;
            var price = 0;
            switch (size) {
                case 'S':
                    price = 250;
                    break;
                case 'M':
                    price = 350;
                    break;
                case 'L':
                    price = 300;
                    break;
                case 'XL':
                    price = 400;
                    break;
            }
            document.getElementById(priceId).value = price * parseInt(document.getElementById(sizeId.replace('Size', 'Qty')).value);
        }
    </script>

</body>
</html>
