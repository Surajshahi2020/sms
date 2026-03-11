<?php
// delete-server.php
// Handles deletion of a server record owned by the logged-in user.
session_start();
include 'includes/authentication.php';
include 'config/dbcon.php';

// ensure user is authenticated via authentication include (should redirect otherwise)
$user_id = isset($_SESSION['auth_user']['user_id']) ? intval($_SESSION['auth_user']['user_id']) : 0;
if ($user_id === 0) {
    $_SESSION['error'] = "कृपया लग इन गर्नुहोस्।";
    header('Location: login.php');
    exit();
}

$server_id = isset($_GET['server_id']) ? intval($_GET['server_id']) : 0;
if ($server_id <= 0) {
    $_SESSION['error'] = "अवैध सर्भर आईडी"; // invalid server ID
    header('Location: step1.php');
    exit();
}

// verify ownership
$check = $con->prepare("SELECT id FROM basic_info WHERE id = ? AND user_id = ?");
$check->bind_param("ii", $server_id, $user_id);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $_SESSION['error'] = "सर्भर फेला परेन वा तपाईंको अनुमति छैन।";
    header('Location: step1.php');
    exit();
}
$check->close();

// perform deletion (hard delete)
$del = $con->prepare("DELETE FROM basic_info WHERE id = ? AND user_id = ?");
$del->bind_param("ii", $server_id, $user_id);
if ($del->execute()) {
    $_SESSION['status'] = "सर्भर सफलतापूर्वक हटाइयो।";
    $_SESSION['msg_type'] = "success";
} else {
    $_SESSION['status'] = "सर्भर हटाउन असफल भयो: " . $con->error;
    $_SESSION['msg_type'] = "error";
}
$del->close();

header('Location: step1.php');
exit();
