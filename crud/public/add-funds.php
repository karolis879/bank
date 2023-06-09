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

            // Update the balance by adding the specified amount
            $data[$index]['balance'] += $amount;

            // Save the updated data back to the JSON file
            file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

            // Redirect back to the account list page
            header('Location: acc-list.php?message=success');
            exit();
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

    <title>Add Funds</title>
</head>

<body>
    <?php require __DIR__ . '/menu.php' ?>

    <div class="remove-bin">
        <h1>Add Funds</h1>

        <?php if (isset($name)) : ?>
            <p>Account Details:</p>
            <p>Name: <?php echo $name; ?></p>
            <p>Last Name: <?php echo $lastName; ?></p>
            <p>Account Number: <?php echo $accNumber; ?></p>
            <p>Person ID: <?php echo $personId; ?></p>
            <p>Balance: <?php echo $balance; ?></p>

            <form method="post" action="">
                <label for="amount">Amount to Add:</label>
                <input style="margin: 10px;" type="number" id="amount" name="amount" min="0" step="0.01" required>
                <button type="submit">Add Funds</button>
            </form>
            <a href="./acc-list.php">
                <button>Go home</button>
            </a>

        <?php endif; ?>
    </div>

</body>

</html>