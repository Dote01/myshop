<?php
include 'config.php';

$query = "SELECT * FROM vehicles";
$stmt = $pdo->prepare($query);
$stmt->execute();
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($vehicles);
?>
