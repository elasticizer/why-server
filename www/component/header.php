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
					href="javascript:void(0)"
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
					<a
						class="nav-link nav-icon-hover"
						href="javascript:void(0)"
						id="drop2"
						data-bs-toggle="dropdown"
						aria-expanded="false"
					>
						<img
							src="https://cdn-icons-png.flaticon.com/512/2815/2815428.png"
							alt=""
							width="35"
							height="35"
							class="rounded-circle"
						>
					</a>
					<div
						class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
						aria-labelledby="drop2"
					>
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
