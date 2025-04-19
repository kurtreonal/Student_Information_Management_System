<?php
function backupToCSV($filename, $headers, $data) {
    $file = fopen($filename, 'a'); // Open file in append mode

    // If file is empty, write headers
    if (filesize($filename) === 0) {
        fputcsv($file, $headers);
    }

    fputcsv($file, $data); // Write the data
    fclose($file);
}
?>