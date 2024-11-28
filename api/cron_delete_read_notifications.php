<?php
include_once '../config/Database.php';
include_once '../models/Notification.php';

$database = new Database();
$db = $database->getConnection();
$notification = new Notification($db);

// Execute delete operation for old read notifications
if ($notification->deleteOldReadNotifications()) {
    echo "Old read notifications deleted successfully.\n";
} else {
    echo "Failed to delete old read notifications.\n";
}
?>
