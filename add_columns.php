<?php
// Run this file to add missing columns to the database
include 'config/dbcon.php';

$columns = [
    'ALTER TABLE server_os_info ADD COLUMN databases_other VARCHAR(255) NULL AFTER installed_databases'
];

echo "Adding columns to server_os_info table...<br>";

foreach ($columns as $sql) {
    $result = mysqli_query($con, $sql);
    if ($result) {
        echo "✓ Success<br>";
    } else {
        $error = mysqli_error($con);
        if (strpos($error, 'Duplicate') !== false || strpos($error, 'already exists') !== false) {
            echo "✓ Already exists<br>";
        } else {
            echo "✗ Error: $error<br>";
        }
    }
}

echo "<br>Done! <a href='step2.php'>Go back to Step 2</a>";
?>
