<?php

require 'vendor/autoload.php';

use App\SQLiteConnection as SQLiteConnection;
use App\JournalistData as JournalistData;

try {

    $pdo = (new SQLiteConnection())->connect();

    $journalistData = new JournalistData($pdo);

    $journalistData->createTable();
} catch (PDOException $e) {
    echo $e->getMessage();
}

$groups = $journalistData->getJournalistGroups();
$ids = $journalistData->getJournalistIds();

$param = [];
if (!empty($_GET['group'])) {
    $param['group'] = $_GET['group'];
}
if (!empty($_GET['id'])) {
    $param['id'] = $_GET['id'];
}
$result = $journalistData->getJournalist($param);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['name']) && !empty($_POST['alias'] && !empty($_POST['group']))) {
        $journalists = [
            [
                'name' => $_POST["name"],
                'alias' => $_POST["alias"],
                'group' => $_POST["group"]
            ]
        ];
        $journalistData->insertJournalist($journalists);
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Újságírók</title>
    <link href="http://v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="text-center">Újságírók</h2>
        <h3>Új újságíró felvétele</h3>
        <form action="/journo/index.php" method="post">
            Név: <input type="text" name="name"><br>
            Alias: <input type="text" name="alias"><br>
            Csoport: <input type="text" name="group"><br>
            <input type="submit">
        </form>
        <h3>Csoportok</h3>
        <div class="page-header">
            <?php foreach ($groups as $group) : ?>
                 <span><a href="/journo/?group=<?= $group ?>"> <?= $group ?> </a> </span>
            <?php endforeach; ?>
        </div>
        <h3>Keresés azonosító alapján</h3>
        <form action="/journo/index.php" method="get">
            <select name="id">
                <?php foreach ($ids as $id) : ?>
                    <option value=<?= $id ?>><?= $id ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" />
        </form>
        <h3 class="text-center">Táblázat</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Azonosító</th>
                    <th>Név</th>
                    <th>Alias</th>
                    <th>Csoport</th>
                </tr>
            </thead>

            <?php echo $result ?>
        </table>
    </div>
</body>

</html>