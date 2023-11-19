<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Type Converter</title>
</head>

<body>
    <h2>Image Type Converter</h2>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lokasi upload file
        $uploadDir = "uploads/";

        // Validasi apakah file adalah file gambar
        $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

        if (in_array($fileType, $allowedTypes)) {
            $fileName = $uploadDir . basename($_FILES["image"]["name"]);
            $newFileName = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . "." . $_POST["format"];

            // Validasi format gambar
            $image = null;
            switch ($fileType) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($_FILES["image"]["tmp_name"]);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($_FILES["image"]["tmp_name"]);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($_FILES["image"]["tmp_name"]);
                    break;
                default:
                    echo "Format gambar tidak valid.";
                    exit;
            }

            if ($image !== false) {
                // Convert image to selected format
                if ($_POST["format"] == 'png') {
                    // Save as PNG (no need for additional processing)
                    imagepng($image, $newFileName, 9);
                } else {
                    // Convert PNG to JPEG (set a white background)
                    $whiteBackground = imagecreatetruecolor(imagesx($image), imagesy($image));
                    $white = imagecolorallocate($whiteBackground, 255, 255, 255);
                    imagefill($whiteBackground, 0, 0, $white);
                    imagecopy($whiteBackground, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    imagejpeg($whiteBackground, $newFileName, 90);
                    imagedestroy($whiteBackground);
                }

                imagedestroy($image);

                echo "File berhasil diupload dan diubah menjadi " . strtoupper($_POST["format"]) . ". <br>";
                echo '<img src="' . $newFileName . '" alt="Converted Image">';
            } else {
                echo "Terjadi kesalahan saat membuka file gambar.";
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
        <label for="format">Pilih Format:</label>
        <select name="format" id="format">
            <option value="jpeg">JPEG</option>
            <option value="png">PNG</option>
            <option value="gif">GIF</option>
        </select>
        <br>
        <input type="submit" value="Upload dan Ubah Tipe">
    </form>
</body>

</html>