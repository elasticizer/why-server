<?php
$title = '課程';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$limit = 10;
$start = $limit * ($page - 1);
$columnsName = [
	'編號',
	'標題',
	'教師',
	'現價*',
	'上架狀態',
	'促銷狀態*',
	'售出數量*',
	'操作',
	'編輯',
];
$columns = [
	'SN', 'Name', 'Intro', 'Syllabus', 'Price', 'WhenApplied',	'WhenLaunched', 'TeacherSN', 'DomainSN', 'ApproverSN',
];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM Course LIMIT ?, ?",
		implode(',', $columns)
	)
);

$statement->execute([$start, $limit]);

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
						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columnsName as $column) : ?>
											<th class="border-bottom-0 fw-semibold mb-0"><?= $column ?></th>
										<?php endforeach ?>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
										<tr>
											<?php foreach ($row as $column) : ?>
												<td class="border-bottom-0 mb-0"><?= $column ?></td>
											<?php endforeach ?>
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
