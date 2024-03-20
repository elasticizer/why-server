<?php
$title = '課程管理';
$layout = './layout/layout.php';
require '../arranger.php';

$page = $_GET['page'] ?? 1;
$limitPerpage = $_GET['limitPerpage'] ?? 10;
$start = $limitPerpage * ($page - 1);
$columnsName = [
	'編號',
	'標題',
	'教師',
	'現價',
	'上架狀態',
	'促銷狀態',
	'售出數量',
	'操作',
	'編輯',
];
$orderValue = $_GET['orderValue'] ?? 'SN ASC';
$query = sprintf("SELECT c1.SN, c1.Name, c1.ApproverSN, Intro, WhenApplied, WhenLaunched, Price, TeacherSN, u1.FirstName, u1.LastName
FROM Course c1
JOIN User u1 ON c1.teacherSN = u1.SN
ORDER BY c1.%s
LIMIT %d, %d", $orderValue, $start, $limitPerpage);
$statement = connect()->query($query);

include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-body p-4">
						<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
						<!-- 排序 -->
						<div class="hstack gap-3">
							<div class="p-2 rounded-4 bg-white py-2 function-wrap hstack">
								<h5 class="m-0">共</h5>
								<h5 id="totalRows" class="m-0"></h5>
								<h5 class="text-nowrap m-0">筆/每頁顯示</h5>
								<ul>
									<?php $options = [5, 10, 20];
									foreach ($options as $option) : ?>
										<a href="?page=1&orderValue=<?= $orderValue ?>&limitPerpage=<?= $option ?>"><?= $option ?></a>
									<?php endforeach; ?>
								</ul>
								<h5 class="m-0">筆</h5>
							</div>
							<div class="p-2 rounded-4 bg-white py-2 function-wrap hstack justify-content-center">
								<h5 class="m-0 text-nowrap">排序方法</h5>

								<select name="orderSelect" id="orderSelect" class="form-select w-50">
									<?php $options = [['courseID由小到大', 'SN ASC'], ['courseID由大到小', 'SN DESC'], ["price由大到小", 'Price DESC'], ["price由小到大", 'Price ASC']];

									foreach ($options as $option) : ?>
										<option <?= $orderValue == $option[1] ? 'selected' : '' ?> value="<?= $option[1] ?>"><?= $option[0] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="p-2 rounded-4 bg-white py-2 function-wrap">
								<h5 class="m-0">顯示方式||篩選</h5>
							</div>
							<div class="p-2 rounded-4 bg-white py-2 function-wrap">
								<a class="btn btn-primary" href="edit.php"><i data-feather="edit"></i>新增課程</a>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columnsName as $c) : ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $c ?></th>
										<?php endforeach ?>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
										<tr>
											<?php
											$soldAmount = connect()->query(sprintf("SELECT * FROM OrderDetail  o1 where o1.CourseSN = %d", $row['SN']))->fetch(PDO::FETCH_NUM)[0] ?? 0;

											// $promotionName = connect()->query(sprintf("SELECT p1.Name FROM Promotion p1 join Course c1 on c1.SN= p1.CourseSN where c1.SN =" . $row['SN'] . " && CURRENT_DATE() BETWEEN p1.WhenStarted AND p1.WhenEnded;"))->fetch(PDO::FETCH_ASSOC);
											$row['promotionName'] = $promotionName ?? '無';
											$states = (!empty($row['ApproverSN']) ? (!empty($row['WhenLaunched']) ? ['btn-dark', '<i data-feather="corner-right-down"></i>下架', '<span class="text-success" >已上架</span>'] : ['btn-warning', '<i data-feather=upload></i>上架', '已下架']) : ['btn-success', ' <i data-feather="check-circle"></i>核准', '<span class ="text-danger">未審核</span>']);
											$orderedRoll = [$row['SN'], $row['Name'], $row['LastName'] . $row['FirstName'], $row['Price'], $states[2], $row['promotionName'], $soldAmount];

											foreach ($orderedRoll as $td) : ?>
												<td class="border-bottom-0 mb-0"><?= $td ?></td>
											<?php endforeach;
											?>
											<td><a class="btn <?= $states[0] ?>" onclick="javascript:approveCourse('<?= $row['SN'] ?>', '<?= isset($row['ApproverSN']) ?: 0 ?>','<?= isset($row['WhenLaunched']) ?: 0 ?>')" data-bs-toggle="modal" data-bs-target='#approveModal'>
													<!-- 按鈕文字 -->
													<?= $states[1] ?>
												</a></td>
											<td class="border-bottom-0 mb-0"><a class="btn btn-primary" href="edit.php?courseID=<?= $row['SN'] ?>&page=<?= $page ?>&orderValue=<?= $orderValue ?>&limitPerpage=<?= $limitPerpage ?>"><i data-feather="edit"></i>編輯</a>
											</td>
										</tr>
									<?php endwhile ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="modalTitle"></h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" id="modalBody">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
				<a class="btn btn-primary" id="approveBtn"></a>
			</div>
		</div>
	</div>
</div>
<!-- JS -->
<script>
	let orderEl = document.getElementById("orderSelect");
	let approveModal = document.getElementById('approveModal');
	let approveBtn = document.getElementById('approveBtn');
	let modalTitle = document.getElementById('modalTitle');
	let modalBody = document.getElementById('modalBody');

	orderEl.onchange = (e) => {
		location = `?page=1&orderValue=${orderEl.value}&limitPerpage=<?= $limitPerpage ?>`;
		console.log(orderEl.value);
	}

	function approveCourse(id, approver, available) {
		console.log(id, approver, available);
		let queryString = `?courseID=${id}&page=<?= $page ?>&orderValue=<?= $orderValue ?>&limitPerpage=<?= $limitPerpage ?>`;
		let courseID = `課號 <span class="text-danger text-decoration-underline">${id}</span> ?`;
		if (approver === '0') {
			approveBtn.href = `api/approve.php${queryString}`; //跳轉到approve頁面
			modalTitle.innerHTML = '是否核准課程';
			modalBody.innerHTML = `操作不可逆。<br>確定核准課程 ${courseID}`;
			approveBtn.innerHTML = '核准';
		} else if (available !== '0') {
			approveBtn.href = `api/launch.php${queryString}`;
			modalTitle.innerHTML = '是否下架課程';
			modalBody.innerHTML = `確定下架課程  ${courseID}`;
			approveBtn.innerHTML = '下架';
		} else {
			approveBtn.href = `api/launch.php${queryString}`;
			modalTitle.innerHTML = '是否上架課程';
			modalBody.innerHTML = `確定上架課程  ${courseID}`;
			approveBtn.innerHTML = '上架';
		}
	}
	<?php

	// const orderSelect = document.getElementById('orderSelect');
	// orderSelect.onchange=(e)=>{
	// 	orderSelect.value=
	// }
	?>
</script>
