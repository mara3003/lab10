<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Results</title>
</head>
<body>
    <h1>Database Results</h1>
    <?php
    // Conectare la baza de date
    $serverName = "mara2002.database.windows.net";
    $connectionOptions = array(
        "Database" => "db",
        "Uid" => "mara",
        "PWD" => "Student20023003"
    );
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    // Verificare conexiune
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Interogare SELECT
    $sql = "SELECT * FROM nume_tabela";
    $result = sqlsrv_query($conn, $sql);

    // Afișare rezultate
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        echo "<p>Filename: " . $row['filename'] . "</p>";
        echo "<p>Blob Store Address: " . $row['blob_store_addr'] . "</p>";
        echo "<p>Time: " . $row['time']->format('H:i:s') . "</p>";
        echo "<p>File Text: " . $row['file_text'] . "</p>";
    }

    // Închidere conexiune
    sqlsrv_close($conn);
    ?>
</body>
</html>
