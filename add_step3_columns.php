<?php
// Run this file to add missing columns for step3 (Data Security) to the database
include 'config/dbcon.php';

$columns = [
    // Table for data security (step3)
    "CREATE TABLE IF NOT EXISTS data_security (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        server_id INT(11) NOT NULL,
        user_id INT(11) NOT NULL,
        
        -- A. Authorization of Data
        auth_data VARCHAR(10) NULL,
        auth_data_details TEXT NULL,
        
        -- B. Authentication of user
        auth_method VARCHAR(100) NULL,
        auth_method_other VARCHAR(255) NULL,
        auth_details TEXT NULL,
        
        -- C. Data Access control for user
        access_rbac VARCHAR(10) NULL,
        access_mac VARCHAR(10) NULL,
        access_dac VARCHAR(10) NULL,
        access_rule VARCHAR(10) NULL,
        access_other_check VARCHAR(10) NULL,
        access_other VARCHAR(255) NULL,
        access_details TEXT NULL,
        
        -- D. Privilege based user
        privilege_based VARCHAR(20) NULL,
        privilege_sop TEXT NULL,
        
        -- E. Encryption
        encryption_method VARCHAR(100) NULL,
        encryption_method_other VARCHAR(255) NULL,
        encryption_details TEXT NULL,
        
        -- F. Off hour administration
        offhour_duty VARCHAR(50) NULL,
        offhour_duty_other VARCHAR(255) NULL,
        duty_sop TEXT NULL,
        offhour_admin VARCHAR(100) NULL,
        offhour_admin_other VARCHAR(255) NULL,
        offhour_details TEXT NULL,
        
        -- Remarks
        remarks TEXT NULL,
        
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        INDEX server_id (server_id),
        INDEX user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

echo "Adding data_security table and columns for step3...<br><br>";

foreach ($columns as $sql) {
    $result = mysqli_query($con, $sql);
    if ($result) {
        echo "✓ Success<br>";
    } else {
        $error = mysqli_error($con);
        if (strpos($error, 'Duplicate') !== false || strpos($error, 'already exists') !== false || strpos($error, 'Table') !== false && strpos($error, 'already exists') !== false) {
            echo "✓ Already exists<br>";
        } else {
            echo "✗ Error: $error<br>";
        }
    }
}

echo "<br>Done! <a href='step3.php'>Go back to Step 3</a>";
?>
