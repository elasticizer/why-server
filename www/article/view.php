<?php
$title = 'Article';
$layout = './layout/layout.php';

require '../arranger.php';

$page = $_GET['page'] ?? 1;
$limit = 10;
$start = $limit * ($page - 1);
$columns = ['SN', 'Identifier', 'Title', 'WhenCreated', 'WhenLastEdited'];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s FROM Article LIMIT ?, ?",
		implode(', ', $columns)
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


						<!-- 分頁功能 -->
						<nav aria-label="...">
							<ul class="pagination">
								<li class="page-item disabled">
									<a class="page-link">Previous</a>
								</li>
								<li class="page-item"><a class="page-link" href="#">1</a></li>
								<li class="page-item active" aria-current="page">
									<a class="page-link" href="#">2</a>
								</li>
								<li class="page-item"><a class="page-link" href="#">3</a></li>
								<li class="page-item">
									<a class="page-link" href="#">Next</a>
								</li>
							</ul>
						</nav>


						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columns as $column) : ?>
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
