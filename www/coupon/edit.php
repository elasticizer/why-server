<?php
$title = (isset($_GET['sn']) ? '編輯' : '新增') . '優惠券';
$layout = './layout/layout.php';

require '../arranger.php';
include find('./component/sidebar.php');
?>

<div class="body-wrapper">
	<?php include find('./component/header.php') ?>

	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title fw-semibold mb-4"><?= $title ?></h5>
				<form>
					<div class="mb-3">
						<label
							for="couponName"
							class="form-label"
						>優惠券名稱</label>
						<input
							type="email"
							class="form-control"
							id="couponName"
							aria-describedby="emailHelp"
						>
						<div
							id="emailHelp"
							class="form-text"
						>We'll never share your email with anyone else.</div>
					</div>
					<!-- Explanation -->
					<div class="mb-3">
						<label
							for="couponInputExplanation"
							class="form-label"
						>說明</label>
						<input
							type="text"
							class="form-control"
							id="couponInputExplanation"
						>
					</div>
					<!-- DiscountRate -->
					<div class="mb-3">
						<label
							for="couponInputDiscountRate"
							class="form-label"
						>折扣</label>
						<input
							type="number"
							class="form-control"
							id="couponInputDiscountRate"
							min="0" max="100" step="10"
						>
					</div>
					<!-- WhenEnded -->
					<div class="mb-3">
						<label
							for="couponInputWhenEnded"
							class="form-label"
						>結束時間</label>
						<input
							type="date"
							class="form-control"
							id="couponInputWhenEnded"
						>
					</div>
					<div class="mb-3 form-check">
						<input
							type="checkbox"
							class="form-check-input"
							id="couponCheck1"
						>
						<label
							class="form-check-label"
							for="couponCheck1"
						>Check me out</label>
					</div>
					<button
						type="submit"
						class="btn btn-primary"
					>提交表單</button>
				</form>
			</div>
		</div>
	</div>
</div>
