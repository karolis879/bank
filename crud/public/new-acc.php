<?php

$error = '';

function validateAsmensKodas($asmensKodas)
{
    if (!preg_match('/^\d{11}$/', $asmensKodas)) {
        return false;
    }
    $skaitmenys = str_split($asmensKodas);
    $kontrolinisSkaitmuo = (int) $skaitmenys[10];

    $skaiciai = array_map('intval', str_split($asmensKodas, 1));

    $svarba = [1, 2, 3, 4, 5, 6, 7, 8, 9, 1];
    $suma = 0;

    for ($i = 0; $i < 10; $i++) {
        $suma += $svarba[$i] * $skaiciai[$i];
    }

    $liekana = $suma % 11;

    if ($liekana === 10) {
        $liekana = 0;
    }

    if ($liekana !== $kontrolinisSkaitmuo) {
        return false;
    }

    return true;
}

function generateIBAN()
{
    $countryCode = 'LT';
    $bankCode = '70440';
    $accountNumber = rand(100000000000000, 999999999999999);

    return $countryCode . $bankCode . $accountNumber;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);

    $name = $_POST['name'];
    $lastName = $_POST['last-name'];
    $personId = $_POST['person-id'];

    // Validate person's ID
    if (!validateAsmensKodas($personId)) {
        $error = 'Person ID is not correct.';
    }

    // Check if a person with the same ID already exists
    foreach ($data as $entry) {
        if ($entry['person-id'] == $personId) {
            $error = 'A person with the same ID already exists.';
            break;
        }
    }

    // Validate name and last name
    if (!preg_match('/^[A-Za-z]+$/', $name) || !preg_match('/^[A-Za-z]+$/', $lastName)) {
        $error = 'Name and last name should contain only letters.';
    }

    if (empty($error)) {
        // Generate IBAN account number
        $accNumber = generateIBAN();

        // Create a new data entry
        $newData = array(
            'name' => $name,
            'last-name' => $lastName,
            'acc-number' => $accNumber,
            'person-id' => $personId,
            'balance' => 0,
        );

        $data[] = $newData;

        $jsonData = json_encode($data);
        file_put_contents('data.json', $jsonData);

        header('Location: ./acc-list.php');
        die;
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

    <title>Document</title>
</head>

<body>
<?php require __DIR__.'/menu.php'?>

    <form method="POST" action="">
        <?php if (!empty($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <label>Vardas:</label>
        <input type="text" name="name" pattern="[A-Za-z]+" required>
        <br>
        <label>Pavardė</label>
        <input type="text" name="last-name" pattern="[A-Za-z]+" required>
        <br>
        <label>Sąskaitos numeris</label>
        <input type="text" name="acc-number" value="<?php echo generateIBAN(); ?>" readonly>
        <br>
        <label>Asmens kodas</label>
        <input type="text" name="person-id" required>
        <br>
        <button type="submit" name="start" value="Pradėti">Create Account</button>
    </form>
</body>

</html>
