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
    try {
        // Conectare la baza de date folosind PDO
        $conn = new PDO("sqlsrv:server = tcp:mara2002.database.windows.net,1433; Database = db", "mara", "Student20023003");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Interogare SELECT
        $sql = "SELECT * FROM fileinfo";
        $stmt = $conn->query($sql);

        // Afișare rezultate
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>Filename: " . $row['filename'] . "</p>";
            echo "<p>Blob Store Address: " . $row['blob_store_addr'] . "</p>";
            echo "<p>Time: " . $row['time'] . "</p>";
            echo "<p>File Text: " . $row['file_text'] . "</p>";
        }
    } catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }
    ?>
</body>
</html>
