<!DOCTYPE html>
<html lang="zh-hant-TW">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?= $title ?></title>
	<style>
		body {
			opacity: 0;
		}

		body.ready {
			opacity: 1;
			transition: opacity 0.5s;
		}

		.feather {
			width: 16px;
			height: 16px;
		}

		.icon {
			width: 24px;
			height: 24px;
		}

		.logo {
			width: 32px;
			height: 32px;
			fill: #3f5faf;
		}
	</style>
	<link rel="stylesheet" href="/static/styles.min.css" />
</head>

<body>
	<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.3/dist/quill.snow.css" rel="stylesheet">
	<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
		<?= $slot ?>
	</div>
	<script>
		(function() {
			window.addEventListener(
				'load',
				() => (
					feather.replace(),
					document.body.classList.add('ready')
				)
			);

			if (location.pathname === '/login.php') return;

			const channel = new BroadcastChannel('logout');

			channel.addEventListener(
				'message',
				() => location.replace('/login.php')
			);
		})();
	</script>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.1/dist/feather.min.js" integrity="sha256-574O2n8joB5Jz047l2w/vaIugl3n5cMowF2uy3dBFc0=" crossorigin="anonymous"></script>
</body>

</html>
