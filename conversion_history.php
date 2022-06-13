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
                No conversions found.
            </section>
        <?php endif ?>
        <section class="conversions-container">
            <div class="table-wrapper">
                <table id="conversion-entries">
                    <?php foreach ($conversionEntries as $entry) : ?>
                        <tr class="entry">
                            <td> Date created: <?= $entry[0] ?> </td>
                            <td> Comment: <?php if (empty($entry[1])) : ?>No comment<?php endif ?> <?= $entry[1] ?> </td>
                            <td> Conversion type: <?= $entry[2] ?> </td>
                            <td> Original file name: <?php if (empty($entry[3])) : ?>Missing<?php endif ?> <?= $entry[3] ?> </td>
                            <td><a href="saved_files/<?= $entry[4] ?>" download>Download Result File</a></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </section>
    </main>
</body>

</html>