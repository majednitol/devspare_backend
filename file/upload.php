<?php

if (!isset($_FILES["myFile"])) {
    die("There is no file to upload.");
}

$filepath = $_FILES['myFile']['tmp_name'];
$fileSize = filesize($filepath);
$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
$filetype = finfo_file($fileinfo, $filepath);

if ($fileSize === 0) {
    die("The file is empty.");
}

if ($fileSize > 3145728) { // 3 MB (1 byte * 1024 * 1024 * 3 (for 3 MB))
    die("The file is too large");
}

$allowedTypes = [
   'image/png' => 'png',
   'image/jpeg' => 'jpg'
];

if (!in_array($filetype, array_keys($allowedTypes))) {
    die("File not allowed.");
}

$filename = basename($filepath); // I'm using the original name here, but you can also change the name of the file here
$extension = $allowedTypes[$filetype];
$targetDirectory = __DIR__ . "../file/public/upload"; // __DIR__ is the directory of the current PHP file

$newFilepath = $targetDirectory . "/" . $filename . "." . $extension;

if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
    die("Can't move file.");
}
unlink($filepath); // Delete the temp file

echo "File uploaded successfully :)";