<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Analysis</title>
    <style>
        body {
            background-color: #f1e6ff; /* Fundal mov deschis */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px; /* Ajustare pentru a include tabelul în dreapta */
            margin: 0 auto;
            display: flex; /* Folosim flexbox pentru a poziționa formularul și tabelul */
            justify-content: space-between; /* Poziționează elementele la margini */
            align-items: flex-start; /* Aliniere sus */
        }

        h1 {
            color: #333;
            font-size: 36px;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            background-color: #9c27b0; /* Butonul mov inchis */
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            flex: 1; /* Ocupă spațiul disponibil */
            margin-right: 20px; /* Spațiu între formular și tabel */
        }

        label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
            color: #fff; /* Text alb pentru contrast */
        }

        input[type="file"] {
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #6a1b9a; /* Butonul mov inchis */
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-size: 18px;
            padding: 10px 20px;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #4a148c; /* Butonul mov mai inchis la hover */
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-top: 50px;
            text-align: center;
        }

        p {
            color: #333;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
        }

        table {
            width: 40%; /* Lățimea tabelului */
            border-collapse: collapse;
            margin-top: 50px;
            background-color: #fff; /* Fundal alb */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        a {
            color: #333;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <h1>Brand Detection</h1> <!-- Modificare text -->
            <label for="image-url">Upload an image:</label>
            <br>
            <input type="file" name="image-url" id="image-url" accept="image/*" required>
            <br>
            <input type="submit" value="Analyze">
        </form>

  <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image-url']) && $_FILES['image-url']['error'] == 0) {
        $sasToken = "sv=2022-11-02&ss=bfqt&srt=sco&sp=rwdlacupiytfx&se=2024-05-18T18:01:47Z&st=2024-05-18T10:01:47Z&spr=https,http&sig=3L4HgNRP%2Ff8DNDy4gSPAaSb4X8e7J%2Bq3Cead7lWDdQg%3D";
        $storageAccount = "storagetema3";
        $containerName = "uploads";
        $blobName = $_FILES['image-url']['name'];
        $filetoUpload = $_FILES['image-url']['tmp_name'];
        $fileLen = filesize($filetoUpload);

        $destinationURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName?$sasToken";
        $newURL = "https://$storageAccount.blob.core.windows.net/$containerName/$blobName";

        $currentDate = gmdate("D, d M Y H:i:s T", time());

        $headers = [
            'x-ms-blob-cache-control: max-age=3600',
            'x-ms-blob-type: BlockBlob',
            'x-ms-date: ' . $currentDate,
            'x-ms-version: 2019-07-07',
            'Content-Type: image/png',
            'Content-Length: ' . $fileLen
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $destinationURL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($filetoUpload));

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            echo $result;
        }

        curl_close($ch);

        $url = "https://tema3mara2002.cognitiveservices.azure.com/vision/v3.2/analyze?visualFeatures=Brands";
        $headers = array(
            "Content-Type: application/json",
            "Ocp-Apim-Subscription-Key: a27a66d696b7424f9bd9dba82e358413",
            "visualFeatures: Brands"
        );

        $data = array(
            "url" => $newURL
        );

        $body = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "Error: " . curl_error($ch);
        }
        curl_close($ch);

        $datas = json_decode($response, true);
        $brands = $datas['brands'];

        $serverName = "sqlservertema3.database.windows.net";
        $username = "mara";
        $password = "Student20023003";
        $database = "database";

    }
  try {
      $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Connection failed: " . $e->getMessage());
    }

    $stmt = $conn->prepare("INSERT INTO details (name, confidence, image_url, detection_time) VALUES (:name, :confidence, :imageUrl, :currentTime)");

    echo '<h2>Brand Analysis Results:</h2>';
    $aux = 0;
  $imageUrl = $data['url'];
    foreach ($brands as $brand) {
      $name = $brand['name'];
      $confidence = $brand['confidence'];
      echo "<p>Brand name: $name</p>";
      echo "<p>Confidence: $confidence</p>";
      echo "<p>Source: <a href='$imageUrl' target='_blank'>Image</a></p>";
      echo "<br>";

      
      $currentTime = date('Y-m-d H:i:s', time());
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':confidence', $confidence);
      $stmt->bindParam(':imageUrl', $newURL);
      $stmt->bindParam(':currentTime', $currentTime);
      try {
        $stmt->execute();
        $aux = 1;
      } catch (PDOException $e) {
        echo "Error executing SQL statement: " . $e->getMessage();
      }
    }

    if ($aux == 0) {
      echo '<p>No Brand Detected.</p>';
    }

    $query = "SELECT * FROM details";
    $result = $conn->query($query);

    echo '<h2>Brand Analysis History:</h2>';
    echo "<table>";
    echo "<tr><th>Name</th><th>Confidence</th><th>Image URL</th><th>Detection Time</th></tr>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['confidence'] . "</td>";
      echo "<td><a href='" . $row['image_url'] . "'target='_blank'>" . $row['image_url'] . "</a></td>";
      echo "<td>" . $row['detection_time'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
}
  ?>

              <div>
            <h2>Brand Analysis Results:</h2>
            <table>
                <!-- Rândurile tabelului -->
            </table>
        </div>
    </div>
</body>

</html>
