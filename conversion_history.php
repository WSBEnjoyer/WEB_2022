<?php

include("util/auth.php");
include("util/conversion_util.php");

$conversionUtil = new ConversionUtil();
$username = $_SESSION["user"];

$conversionEntries = $conversionUtil->getConversionsForCurrentUser($username);

$rowsCount = count($conversionEntries);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>История</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/table_style.css" />
</head>

<body>
    <header>
        <nav>
            <a href="index.php">Назад</a>
        </nav>
    </header>
    <main>
        <img class="history-img" src="./resources/history.png">
        <h1>История на конвертиранията</h1>
        <?php if ($rowsCount < 1) : ?>
            <section class="failure">
                Не са намерени конвертирания.
            </section>
        <?php endif ?>
        <section class="conversions-container">
            <div class="table-wrapper">
                <table id="conversion-entries">
                    <?php foreach ($conversionEntries as $entry) : ?>
                        <tr class="entry">
                            <td> Дата: <?= $entry[0] ?> </td>
                            <td> Коментар: <?php if (empty($entry[1])) : ?>Няма коментар<?php endif ?> <?= $entry[1] ?> </td>
                            <td> Тип на конвертиране: <?= $entry[2] ?> </td>
                            <td> Име на първоначален файл: <?php if (empty($entry[3])) : ?>Липсва<?php endif ?> <?= $entry[3] ?> </td>
                            <td><a href="saved_files/<?= $entry[4] ?>" download>Изтегляне на резултатен файл</a></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </section>
    </main>
</body>

</html>