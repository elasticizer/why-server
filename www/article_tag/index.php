<?php
$title = '文章標籤';
$layout = './layout/layout.php';

require '../arranger.php';

$page = intval($_GET['page'] ?? 1);
$table = 'ArticleTag';
$total = connect()->query("SELECT COUNT(*) FROM {$table}")->fetch()[0];
$limit = 10;
$pages = ceil($total / $limit);
$start = $limit * ($page - 1);

$columns = ['ArticleSN', 'TagSN'];
$statement = connect()->prepare(
	sprintf(
		"SELECT %s AS Count FROM %s LIMIT ?, ?",
		implode(', ', $columns),
		$table
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
						<section class='d-flex justify-content-between align-items-center'>
							<div>
								<nav aria-label="...">
									<ul class="pagination">
										<li class="page-item ">
											<a class="page-link <?= $page > 1 ? '' : 'disabled' ?>" href="?page=1">
												<i data-feather="chevrons-left"></i>
											</a>
										</li>
										<?php if ($page > 1) : ?>
											<li class="page-item">
												<a class="page-link" href="?page=<?= $page - 1 ?>"><?= $page - 1 ?></a>
											</li>
										<?php endif ?>
										<li class="page-item active" aria-current="page">
											<a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
										</li>
										<?php if ($page < $pages) : ?>
											<li class="page-item">
												<a class="page-link" href="?page=<?= $page + 1 ?>"><?= $page + 1 ?></a>
											</li>
										<?php endif ?>
										<li class="page-item">
											<a class="page-link <?= $page < $pages ? '' : 'disabled' ?>" href="?page=<?= $pages ?>">
												<i data-feather="chevrons-right"></i>
											</a>
										</li>
									</ul>
								</nav>
							</div>
							<div><a href="./edit.php" class="btn btn-success m-1">新增</a></div>
						</section>

						<div class="table-responsive">
							<table class="table text-nowrap mb-0 align-middle">
								<thead class="text-dark fs-4">
									<tr>
										<?php foreach ($columns as $column) : ?>
											<th class="border-bottom-1 fw-semibold mb-0"><?= $column ?></th>
										<?php endforeach ?>
										<th class="border-bottom-1 fw-semibold mb-0"></th>
									</tr>
								</thead>
								<tbody>
									<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
										<tr>
											<?php foreach ($row as $column) : ?>
												<td class="border-bottom-0 mb-0"><?= $column ?></td>
											<?php endforeach ?>
											<td class="border-bottom-0 mb-0">
												<a href="edit.php?ArticleSN=<?= $row['ArticleSN'] ?>" class="btn btn-info m-1">編輯</a>
												<a href="delete.php?ArticleSN=<?= $row['ArticleSN'] ?>" class="btn btn-danger m-1" onclick="event.preventDefault(), confirm('確定要刪除該筆資料？') && (location.href = this.href)">刪除</a>
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
