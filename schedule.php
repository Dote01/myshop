<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $origin_lat = $_POST['origin_lat'];
    $origin_lng = $_POST['origin_lng'];
    $destination_lat = $_POST['destination_lat'];
    $destination_lng = $_POST['destination_lng'];
    $estimated_delivery = $_POST['estimated_delivery'];

    $query = "INSERT INTO shipments (user_id, vehicle_id, status, origin, destination, estimated_delivery) 
              VALUES (?, ?, 'pending', POINT(?, ?), POINT(?, ?), ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id, $vehicle_id, $origin_lat, $origin_lng, $destination_lat, $destination_lng, $estimated_delivery]);

    // Send notification
    $notification_query = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
    $notification_stmt = $pdo->prepare($notification_query);
    $notification_stmt->execute([$user_id, 'Your shipment has been scheduled.']);

    echo json_encode(['status' => 'success']);
}
?>
