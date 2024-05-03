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

try {
    $conn = new PDO("sqlsrv:server = tcp:mara2002.database.windows.net,1433; Database = db", "mara", "Student20023003");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "mara", "pwd" => "Student20023003", "Database" => "db", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:mara2002.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

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
