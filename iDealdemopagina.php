<?php
include __DIR__ . "/header.php";
include "cartfuncties.php";

if (isset($_GET["id"])) {
    $stockItemID = $_GET["id"];
} else {
    $stockItemID = 0;
}

//Refreshed de pagina op het moment dat er een post plaats vindt
//De pagina wordt direct gerefreshed als er post plaatsvindt.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Refresh:0");
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Betalen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .titel {
            text-align: center;
            border: 1px solid #FFFFFF;
            padding: 10px;
            width: 100%;
            border-radius: 20px;
        }

        .betalen {
            margin-top: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        form {
            margin: 0;
        }

        .qr-code {
            text-align: center;
        }

        .qr-code img {
            width: 150px;
            height: 150px;
        }

        .Terug {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="titel">
        <h1>Ideal</h1>
    </div>

    <div class="betalen">
        <h1>Bank invoeren</h1>
        <div class="grid-container">
            <form action="" method="post">
                <label for="naam">Voer je bank in:</label>
                <input type="text" id="naam" name="naam" required>
                <div class="betalen">
                    <form method="post">
                        <button type='submit' name='betalen' class="IdealKnop" id="AfrekenenKnop">Betalen</button>
                    </form>
                </div>
            </form>
            <div class="qr-code">
                <h1>QR-code</h1>
                <?php
                $thankYouUrl = 'https://www.example.com/thankyou.php';

                $googleChartsApiUrl = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($thankYouUrl);
                ?>
                <img src="<?php echo $googleChartsApiUrl; ?>" alt="QR-code">
            </div>
        </div>
    </div>

    <?php
    $cart = getCart();
    $connection = connectToDatabase();

    foreach ($cart as $Artikelnummer => $aantal) {

        $productDetails = getStockItem($Artikelnummer, $connection);
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['betalen'])) {

            foreach ($cart as $Artikelnummer => $aantal) {
                updateQuantityOnHand($Artikelnummer, $aantal, $connection);
            }

            mysqli_close($connection);

            unset($_SESSION['cart']);
        }
    }
    ?>

    <div class="Terug">
        <form action="afrekenen.php" method="post">
            <button type="submit" id="AfrekenenKnop">Annuleren</button>
        </form>
    </div>
</div>

</body>
</html>
