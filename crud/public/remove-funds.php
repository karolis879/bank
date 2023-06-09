<?php

if (isset($_GET['index'])) {
    $index = $_GET['index'];

    // Load the existing data from JSON file
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);

    // Check if the account exists at the specified index
    if (isset($data[$index])) {
        // Get the account details
        $name = $data[$index]['name'];
        $lastName = $data[$index]['last-name'];
        $accNumber = $data[$index]['acc-number'];
        $personId = $data[$index]['person-id'];
        $balance = $data[$index]['balance'];


        // Check if the form is submitted
        if (isset($_POST['amount'])) {
            $amount = $_POST['amount'];

            // Check if the amount is non-negative and does not exceed the available balance
            if ($amount >= 0 && $amount <= $balance) {
                // Update the balance by subtracting the specified amount
                $data[$index]['balance'] -= $amount;

                // Save the updated data back to the JSON file
                file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

                // Redirect back to the account list page
                header('Location: acc-list.php?message=success2');
                exit();
            } else {
                // Amount is negative or exceeds the available balance, display an error message
                $error = "Invalid amount entered.";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="app.css">

    <title>Remove Funds</title>
</head>

<body>
    <?php require __DIR__ . '/menu.php' ?>

    <div class="remove-bin">
        <h1>Remove Funds</h1>

        <?php if (isset($name)) : ?>
            <p>Account Details:</p>
            <p>Name: <?php echo $name; ?></p>
            <p>Last Name: <?php echo $lastName; ?></p>
            <p>Account Number: <?php echo $accNumber; ?></p>
            <p>Person ID: <?php echo $personId; ?></p>
            <p>Balance: <?php echo $balance; ?></p>

            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <label for="amount">Amount to Remove:</label>
                <input style="width: 200px; margin: 10px" type="number" id="amount" name="amount" min="0" step="0.01" max="<?php echo $balance; ?>" required>
                <button type="submit">Remove Funds</button>
            </form>
            <a href="./acc-list.php">
                <button style="margin-top: 30px; background-color: crimson">Go home</button>
            </a>

        <?php endif; ?>
    </div>

</body>

</html>