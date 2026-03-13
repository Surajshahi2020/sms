<?php
// Run this file to add/create network_security table for step4
include 'config/dbcon.php';

$sql = "CREATE TABLE IF NOT EXISTS network_security (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    server_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    
    -- A. Firewall
    firewall_status VARCHAR(10) NULL,
    firewall_vendor VARCHAR(255) NULL,
    firewall_model VARCHAR(255) NULL,
    firewall_detail TEXT NULL,
    
    -- B. Firewall architecture
    multivendor_status VARCHAR(10) NULL,
    multivendor_detail TEXT NULL,
    cascaded_status VARCHAR(10) NULL,
    cascaded_type VARCHAR(50) NULL,
    cascaded_detail TEXT NULL,
    
    -- C. Types of Firewall
    firewall_types TEXT NULL,
    firewall_types_detail TEXT NULL,
    
    -- D. Network Segmentation
    segmentation_status VARCHAR(10) NULL,
    segmentation_detail TEXT NULL,
    
    -- E. IDS/IPS
    ids_status VARCHAR(10) NULL,
    ids_type VARCHAR(100) NULL,
    ids_detail TEXT NULL,
    
    -- F. VPN
    vpn_status VARCHAR(10) NULL,
    vpn_type VARCHAR(100) NULL,
    vpn_detail TEXT NULL,
    
    -- G. Network Monitoring
    monitoring_status VARCHAR(10) NULL,
    monitoring_tools VARCHAR(255) NULL,
    monitoring_detail TEXT NULL,
    
    -- H. Ports and Services
    ports_review VARCHAR(50) NULL,
    unused_services TEXT NULL,
    ports_detail TEXT NULL,
    
    -- Review Information
    next_review_date DATE NULL,
    reviewer_name VARCHAR(255) NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX server_id (server_id),
    INDEX user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

echo "Creating network_security table for step4...<br><br>";

$result = mysqli_query($con, $sql);
if ($result) {
    echo "✓ Success - Table created<br>";
} else {
    $error = mysqli_error($con);
    if (strpos($error, 'already exists') !== false) {
        echo "✓ Table already exists<br>";
    } else {
        echo "✗ Error: $error<br>";
    }
}

echo "<br>Done! <a href='step4.php'>Go back to Step 4</a>";
?>
