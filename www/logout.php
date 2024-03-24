<?php

session_start();
session_destroy();

?>

<script>
	(function () {
		const channel = new BroadcastChannel('logout');

		channel.postMessage(null);
		alert('已經登出囉！');
		location.replace('/login.php');
	})();
</script>
