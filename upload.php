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


use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;

// Includeți biblioteca Azure Storage
require_once "vendor/autoload.php";

// Configurați conexiunea cu Blob Storage
$connectionString = "DefaultEndpointsProtocol=https;AccountName=storagemara2002;AccountKey=ahWcMAuzfsm3PiafsJsQsf8LG7y/+XveWWboUn3DQgbDDBeAdYa2+nKO+V3lnNZbevlSPb8adr4L+AStMS5yIA==;EndpointSuffix=core.windows.net";
$blobClient = BlobRestProxy::createBlobService($connectionString);

// Calea către fișierul încărcat pe server
$filePath = $_FILES["uploaded_file"]["tmp_name"];

// Numele fișierului în Blob Storage
$blobName = "uploaded_files/" . $_FILES["uploaded_file"]["name"];

// Încărcați fișierul în Blob Storage
$content = fopen($filePath, "r");
$blobClient->createBlockBlob("container1", $blobName, $content);

// Închideți fișierul după încărcare
fclose($content);

// Ștergeți fișierul de pe serverul de aplicații
unlink($filePath);

$fileName = $_FILES["uploaded_file"]["name"];
$blobUrl = "https://storagemara2002.blob.core.windows.net/container1/$blobName";

// Inserați informațiile în baza de date
$sql = "INSERT INTO files (file_name, blob_url) VALUES ('$fileName', '$blobUrl')";

if ($conn->query($sql) === TRUE) {
    echo "Record inserted successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>
