<?php

const API = 'https://ithelp.ithome.com.tw/m/api';

extract($argv[1], $argv[2]);

function fetch($articleId) {
	$href = API . "/articles/{$articleId}";
	$json = file_get_contents($href);
	$data = json_decode($json, true)['data'];

	[
		'subject' => $subject,
		'description' => $content,
		'ironman' => [
			'subject' => $series
		]
	] = $data['article'];

	return [
		'Identifier' => $articleId,
		'Title' => trim($subject),
		'Content' => trim($content),
		'SeriesSN' => trim($series),
		'AuthorSN' => 2
	];
}

function amass($user, $ironman) {
	$i = 1;
	$list = [];

	do {
		$href = API . "/users/{$user}/ironman/{$ironman}?page={$i}";
		$json = file_get_contents($href);
		$data = json_decode($json, true)['data'];

		if (empty ($data['articles'])) {
			break;
		}

		$list = array_reduce(
			$data['articles'],
			fn($c, $i) => [...$c, $i['article_id']],
			$list
		);
	} while ($i++);

	return $list;
}

function extract($user, $ironman) {
	$list = array_map(
		'fetch',
		amass($user, $ironman)
	);

	file_put_contents(
		"{$user}-{$ironman}.json",
		json_encode($list)
	);
}
