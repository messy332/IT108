<?php

$source = 'storage/app/public/crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg';
$dest = 'storage/app/public/crops/harvested.jpg';

if (!file_exists($source)) {
    echo "Source file not found: $source\n";
    exit(1);
}

if (file_exists($dest)) {
    echo "harvested.jpg already exists\n";
    exit(0);
}

if (copy($source, $dest)) {
    echo "✓ Successfully created harvested.jpg\n";
    echo "File size: " . filesize($dest) . " bytes\n";
} else {
    echo "✗ Failed to create harvested.jpg\n";
    exit(1);
}
?>
