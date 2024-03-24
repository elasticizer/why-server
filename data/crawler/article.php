<?php

require_once 'module.php';

const API = 'https://ithelp.ithome.com.tw/m/api';

$db = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/../why.db');

foreach (array_slice($argv, 1) as $series) {
	[$user, $ironman] = explode('/ironman/', $series);

	foreach (amass($user, $ironman) as $article) {
		echo fetch($article) . PHP_EOL;
	}
}

function fetch($article) {
	$href = API . "/articles/{$article}";
	$json = file_get_contents($href);
	$data = json_decode($json, true)['data']['article'];

	$user = write('User', [
		'Username' => $username = $data['author']['account'],
		null,
		'FirstName' => '',
		'LastName' => '',
		'Nickname' => $data['author']['nickname'],
		'E-mail' => $username . '@example.edu'
	]);
	$series = write('Series', [
		'Identifier' => $data['ironman']['series_id'],
		null,
		'Name' => $data['ironman']['subject'],
		'AuthorSN' => $user
	]);
	$category = write('Category', [
		'Identifier' => $data['ironman']['group'],
		null,
		'Name' => $data['ironman']['name'],
		'Intro' => '',
		'CreatorSN' => 0
	]);
	$article = write('Article', [
		'Identifier' => $article,
		null,
		'Title' => $data['subject'],
		'Content' => $data['description'],
		'WhenCreated' => date('Y-m-d H:i:s', $data['created_at'] / 1000),
		'WhenLastEdited' => date('Y-m-d H:i:s', $data['updated_at'] / 1000),
		'SeriesSN' => $series,
		'AuthorSN' => $user
	]);
	write('ArticleCategory', [
		'ArticleSN' => $article,
		'CategorySN' => $category,
		null
	]);

	return $article;
}

function amass($user, $ironman) {
	$i = 1;
	$list = [];

	echo 'Amassing...' . PHP_EOL;

	do {
		$href = API . "/users/{$user}/ironman/{$ironman}?page={$i}";
		$json = file_get_contents($href);
		$data = json_decode($json, true)['data'];

		if (!isset($data['articles'])) {
			break;
		}

		$list = array_merge(
			$list,
			array_column($data['articles'], 'article_id')
		);
	} while ($i++);

	return $list;
}
