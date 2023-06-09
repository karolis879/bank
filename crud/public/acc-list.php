<?php

// Function to display a message and set it to disappear after a specified duration
function displayMessage($message, $durationSeconds = 5)
{
    echo '<div class="message">' . $message . '</div>';
    echo '<script>
        setTimeout(function() {
            var messageElement = document.querySelector(".message");
            if (messageElement && messageElement.textContent === "' . $message . '") {
                messageElement.style.display = "none";
            }
        }, ' . ($durationSeconds * 1000) . ');
    </script>';
}

if (isset($_POST['delete'])) {
    $index = $_POST['delete'];

    // Load the existing data from JSON file
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);

    // Check if the account exists at the specified index
    if (isset($data[$index])) {
        $balance = $data[$index]['balance'];

        // Check if the account has funds
        if ($balance == 0) {
            // Remove the account entry at the specified index
            unset($data[$index]);

            // Save the updated data back to the JSON file
            file_put_contents('data.json', json_encode(array_values($data), JSON_PRETTY_PRINT));

            // Display a success message for 5 seconds
            displayMessage('Account deleted successfully!', 5);
        } else {
            // Account has funds, display an error message for 10 seconds
            displayMessage('Cannot delete account. Funds exist in the account.', 10);
        }
    }
}

$jsonData = file_get_contents('data.json');
$data = json_decode($jsonData, true);

// Sort the data array by last name
usort($data, function ($a, $b) {
    return strnatcmp($a['last-name'], $b['last-name']);
});

$tableHtml = '<table id="customers">
                <tr>
                    <th>Name</th>
                    <th>Last name</th>
                    <th>Acc number</th>
                    <th>Person ID</th>
                    <th>Balance</th>
                    <th></th>
                </tr>';

foreach ($data as $index => $entry) {
    $name = $entry['name'];
    $lastName = $entry['last-name'];
    $accNumber = $entry['acc-number'];
    $personId = $entry['person-id'];
    $balance = $entry['balance'];

    $tableHtml .= '<tr>
    <td>' . $name . '</td>
    <td>' . $lastName . '</td>
    <td>' . $accNumber . '</td>
    <td>' . $personId . '</td>
    <td>' . $balance . '</td>
    <td style="display:flex; justify-content:space-between">
        <a style="padding-right: 10px;" href="./add-funds.php?index=' . $index . '">Pridėti</a>
        <a href="./remove-funds.php?index=' . $index . '">Atimti</a>
        <form method="post" action="">
        <input type="hidden" name="delete" value="' . $index . '">
        <button type="submit">Ištrinti</button>
    </form>
    </td>
</tr>';
}

$tableHtml .= '</table>';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="app.css">
    <script src="app.js"></script>
    <title>Bank</title>
  
</head>

<body>
  <?php require __DIR__.'/menu.php'?>
    <h1>Sąskaitos</h1>

    <?php if (isset($_GET['message']) && $_GET['message'] === 'success') : ?>
        <?php echo 'Pavyko!!' ?>
    <?php endif; ?>
    <?php if (isset($_GET['message']) && $_GET['message'] === 'success2') : ?>
        <?php displayMessage('Funds removed successfully!', 3); ?>
    <?php endif; ?>

    <?php echo $tableHtml; ?>

</body>

</html>
