<?php
require '../arranger.php';

$statement = connect()->prepare("SELECT COUNT(*) FROM `Category` WHERE `Identifier` = ?");
$statement->execute([$_GET['identifier'] ?? '']);
$count = $statement->fetchColumn();

header('Content-Type: application/json');

echo json_encode([
	'existent' => boolval($count)
]);
