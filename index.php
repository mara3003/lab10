<!DOCTYPE html>
<html>

<head>
  <title>Brand Analysis</title>
  <style>
    body {
      background-color: #f7f0ff;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #6a1b9a;
      font-size: 36px;
      margin: 0 0 30px;
      text-align: center;
    }

    form {
      text-align: center;
    }

    label {
      display: block;
      font-size: 18px;
      margin-bottom: 10px;
    }

    input[type="file"] {
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
      padding: 10px;
      width: 100%;
      margin-bottom: 20px;
    }

    input[type="submit"] {
      background-color: #6a1b9a;
      border: none;
      border-radius: 6px;
      color: #fff;
      cursor: pointer;
      font-size: 18px;
      padding: 10px 20px;
      margin-top: 20px;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #4a148c;
    }

    h2 {
      color: #6a1b9a;
      font-size: 24px;
      margin: 50px 0 30px;
      text-align: center;
    }

    p {
      color: #333;
      font-size: 16px;
      margin: 20px 0;
      text-align: center;
    }

    a {
      color: #6a1b9a;
      font-weight: bold;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 50px;
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
  </style>
</head>

<body>
  <div class="container">
    <h1>Brand Analysis</h1>
    <form method="POST" enctype="multipart/form-data">
      <label for="image">Upload an image:</label>
      <br>
      <input type="file" name="image-url" id="image-url" accept="image/*" required>
      <br>
      <input type="submit" value="Analyze">
    </form>
    <br><br>
    <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image-url']) && $_FILES['image-url']['error'] == 0) {
        $sasToken = "sv=2022-11-02&ss=bfqt&srt=sco&sp=rwdlacupiytfx&se=2024-05-30T17:06:01Z&st=2024-05-19T09:06:01Z&spr=https&sig=WsHKHgXye3Qqg7gLPW1PHRyrE0FkrrLF2j0fsfolN84%3D";
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

 try {
      $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Connection failed: " . $e->getMessage());
    }

    $stmt = $conn->prepare("INSERT INTO details (name, confidence, image_url, detection_time) VALUES (:name, :confidence, :imageUrl, :currentTime)");

        $imageUrl = $data['url'];
    echo '<h2>Brand Analysis Results:</h2>';
    $aux = 0;
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
}
  ?>
  </div>
</body>

</html>
