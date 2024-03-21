<?php
include __DIR__ . '/../../arranger.php';

$postData = file_get_contents("php://input");
$data = json_decode($postData, true); //true轉換為php陣列，否則轉為php物件

$page = $data['page'];
$orderValue = $data['orderValue'];
$limitPerpage = $data['limitPerpageVa']??5;
$otherLimit = isset($data['otherLimit']) ? intval($data['otherLimit']) : 0;

$start = $limitPerpage * ($page - 1);

$output = [
	'limits' => $data, #除錯用
];

$query = sprintf("SELECT c1.SN, c1.Name, c1.ApproverSN,c1.Intro, WhenCreated, WhenLaunched, Price, TeacherSN, u1.Nickname
FROM Course c1
JOIN User u1 ON c1.teacherSN = u1.SN
ORDER BY c1.%s
LIMIT %d, %d", $orderValue, $start, $limitPerpage);
$statement = connect()->query($query);
$allRows=[];
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
	$soldAmount = connect()->query(sprintf("SELECT * FROM OrderDetail  o1 where o1.CourseSN = %d", $row['SN']))->fetch(PDO::FETCH_NUM)[0] ?? 0;

	// $promotionName = connect()->query(sprintf("SELECT p1.Name FROM Promotion p1 join Course c1 on c1.SN= p1.CourseSN where c1.SN =" . $row['SN'] . " && CURRENT_DATE() BETWEEN p1.WhenStarted AND p1.WhenEnded;"))->fetch(PDO::FETCH_ASSOC);
	$row['promotionName'] = $promotionName ?? '無';
	$states = (isset($row['ApproverSN']) ? (!empty($row['WhenLaunched']) ? ['btn-dark', '<i data-feather="corner-right-down"></i>下架', '<span class="text-success" >已上架</span>'] : ['btn-warning', '<i data-feather=upload></i>上架', '下架']) : ['btn-success', ' <i data-feather="check-circle"></i>核准', '<span class ="text-danger">未審核</span>']);
	$orderedRoll = [$row['SN'], $row['Name'],$row['Nickname'], $row['Price'], $states, $row['promotionName'], $soldAmount];
	array_push($allRows,$orderedRoll);
}


$total_rows_sql = "SELECT count(1) from Course";
$total_rows_stmt = connect()->query("$total_rows_sql");
$totalRows = $total_rows_stmt->fetch(PDO::FETCH_NUM)[0];


$totalPages = ceil($totalRows / $limitPerpage);

$output['totalRows'] = $totalRows;
$output['totalPages'] = $totalPages;
$output['rows'] = $allRows;

echo json_encode($output, JSON_UNESCAPED_UNICODE);
