<?php

$sourceFile = 'storage/app/public/crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';
$destFile = 'storage/app/public/crops/harvest.jpg';

if (!file_exists($sourceFile)) {
    echo "Source file not found: $sourceFile\n";
    exit(1);
}

if (copy($sourceFile, $destFile)) {
    echo "Successfully created harvest.jpg\n";
    echo "File size: " . filesize($destFile) . " bytes\n";
} else {
    echo "Failed to copy file\n";
    exit(1);
}
?>
