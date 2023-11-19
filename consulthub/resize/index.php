<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Resizer</title>
</head>

<body>
    <h2>Image Resizer</h2>

    <?php
    // Fungsi untuk meresize gambar
    function resizeImage($sourcePath, $targetPath, $newWidth, $newHeight)
    {
        list($originalWidth, $originalHeight) = getimagesize($sourcePath);

        $imageResized = imagecreatetruecolor($newWidth, $newHeight);

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

        imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
        imagedestroy($image);

        imagejpeg($imageResized, $targetPath, 90); // Ubah sesuai dengan jenis gambar yang diunggah
        imagedestroy($imageResized);
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
                // Tentukan ukuran baru (dalam piksel)
                $newWidth = $_POST["width"];
                $newHeight = $_POST["height"];

                // Lokasi penyimpanan file yang diresize
                $resizedFileName = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . "_resized.jpg";

                // Resize gambar
                resizeImage($fileName, $resizedFileName, $newWidth, $newHeight);

                echo "File berhasil diupload dan diresize. <br>";
                echo '<img src="' . $resizedFileName . '" alt="Resized Image">';
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
        <label for="width">Lebar Baru (px):</label>
        <input type="number" name="width" id="width" required>
        <br>
        <label for="height">Tinggi Baru (px):</label>
        <input type="number" name="height" id="height" required>
        <br>
        <input type="submit" value="Upload dan Resize">
    </form>
</body>

</html>