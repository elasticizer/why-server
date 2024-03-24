<?php
$list = [
	'帳戶' => 'account.php'
];
?>

<header class="app-header">
	<nav class="navbar navbar-expand-lg navbar-light">
		<ul class="navbar-nav">
			<li class="nav-item d-block d-xl-none">
				<a
					class="nav-link sidebartoggler nav-icon-hover"
					id="headerCollapse"
				>
					<i data-feather="menu"></i>
				</a>
			</li>
		</ul>
		<div
			class="navbar-collapse justify-content-end px-0"
			id="navbarNav"
		>
			<ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
				<li class="nav-item dropdown d-flex align-items-center">
					<div
						class="nav-link nav-icon-hover"
						id="dropdown"
						data-bs-toggle="dropdown"
						role="button"
					>
						<i data-feather="user" class="icon"></i>
					</div>
					<div class="h6 mb-0" data-bs-toggle="dropdown" role="button">
						<span><?= connect()->query(
							"SELECT `FirstName` FROM `Staff` WHERE `SN` = {$_SESSION['sn']}"
						)->fetchColumn() ?></span>
					</div>
					<div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up">
						<div class="message-body">
							<?php foreach ($list as $text => $href): ?>
								<a
									href="<?= $href ?>"
									class="d-flex align-items-center gap-2 dropdown-item"
								>
									<span class="fs-3"><?= $text ?></span>
								</a>
							<?php endforeach ?>
							<a
								href="/logout.php"
								class="btn btn-outline-primary mx-3 mt-2 d-block"
							>登出</a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</nav>
</header>
