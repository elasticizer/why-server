<?php
$menu = [
	'職員' => [
		'職員' => '/staff/',
		'群組' => '/group/'
	],
	'課程' => [
		'課程' => '/course/',
		'領域' => '/domain/'
	],
	'交易' => [
		'訂單' => '/order/',
		'優惠券' => '/coupon/',
	],
	'部落格' => [
		'文章' => '/article/',
		'分類' => '/category/',
		'標籤' => '/tag/'
	],
	'網站' => [
		'頁面' => '/page/',
		'網頁元件' => '/component/'
	]
];
?>

<aside class="left-sidebar">
	<div>
		<div class="brand-logo d-flex align-items-center justify-content-between">
			<div
				class="close-btn d-xl-none d-block sidebartoggler cursor-pointer"
				id="sidebarCollapse"
			>
				<i data-feather="x"></i>
			</div>
		</div>
		<nav class="sidebar-nav scroll-sidebar">
			<ul id="sidebarnav">
				<?php foreach ($menu as $name => $list): ?>
					<li class="nav-small-cap">
						<span class="hide-menu"><?= $name ?></span>
					</li>
					<?php foreach ($list as $text => $href): ?>
						<li class="sidebar-item">
							<a
								class="sidebar-link <?=
									str_starts_with($_SERVER['REQUEST_URI'], $href) ? 'active' : ''
									?>"
								href="<?= $href ?>"
								aria-expanded="false"
							>
								<span class="hide-menu"><?= $text ?></span>
							</a>
						</li>
					<?php endforeach ?>
				<?php endforeach ?>
			</ul>
		</nav>
	</div>
</aside>
