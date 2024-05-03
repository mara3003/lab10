<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
</head>
<body>
    <h2>Încărcați un fișier:</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Selectați fișierul:</label>
        <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
        <label for="fileDescription">Descriere:</label>
        <input type="text" name="fileDescription" id="fileDescription"><br><br>
        <input type="submit" value="Încărcați Fișierul" name="submit">
    </form>
  <?php

  $serverName = "nume_server.database.windows.net";
$connectionOptions = array(
    "Database" => "db",
    "Uid" => "mara",
    "PWD" => "Student20023003"
);

// Realizăm conexiunea la baza de date SQL
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    echo "Eroare la conectarea la baza de date SQL.";
    die(print_r(sqlsrv_errors(), true));
}

  // Selectăm toate înregistrările din tabela fileinfo
$sqlSelect = "SELECT * FROM fileinfo";
$stmtSelect = sqlsrv_query($conn, $sqlSelect);
if ($stmtSelect === false) {
    echo "Eroare la selectarea fișierelor din baza de date SQL.";
    die(print_r(sqlsrv_errors(), true));
}

echo "<h2>Fișiere încărcate:</h2>";
echo "<ul>";
while ($row = sqlsrv_fetch_array($stmtSelect, SQLSRV_FETCH_ASSOC)) {
    echo "<li><a href='" . $row['blob_store_addr'] . "' target='_blank'>" . $row['filename'] . "</a> - " . $row['file_text'] . "</li>";
}
echo "</ul>";


  ?>
</body>
</html>
