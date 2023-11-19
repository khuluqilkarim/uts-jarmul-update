<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Size Reducer</title>
</head>

<body>
    <h2>Image Size Reducer</h2>

    <?php
    // Fungsi untuk mereduksi ukuran file gambar
    function reduceImageSize($sourcePath, $targetPath, $compressionQuality)
    {
        $image = null;
        $fileType = mime_content_type($sourcePath);

        switch ($fileType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                echo "Format gambar tidak valid.";
                exit;
        }

        imagejpeg($image, $targetPath, $compressionQuality);
        imagedestroy($image);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lokasi upload file
        $uploadDir = "uploads/";

        // Pastikan direktori upload ada
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validasi apakah file adalah file gambar
        $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

        if (in_array($fileType, $allowedTypes)) {
            $fileName = $uploadDir . basename($_FILES["image"]["name"]);

            // Pindahkan file dari direktori sementara ke direktori upload
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $fileName)) {
                // Tentukan kompresi kualitas (0-100, 100 adalah kualitas terbaik)
                $compressionQuality = $_POST["quality"];

                // Lokasi penyimpanan file yang direduksi
                $reducedFileName = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . "_reduced.jpg";

                // Mereduksi ukuran file gambar
                reduceImageSize($fileName, $reducedFileName, $compressionQuality);

                echo "File berhasil diupload dan direduksi ukurannya. <br>";
                echo '<img src="' . $reducedFileName . '" alt="Reduced Image">';
            } else {
                echo "Gagal mengupload file.";
            }
        } else {
            echo "Hanya file gambar dengan format JPEG, PNG, dan GIF yang diperbolehkan.";
        }
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Pilih Gambar:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <br>
        <label for="quality">Kualitas Kompresi (0-100):</label>
        <input type="number" name="quality" id="quality" min="0" max="100" value="80" required>
        <br>
        <input type="submit" value="Upload dan Reduksi Ukuran">
    </form>
</body>

</html>