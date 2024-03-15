<?php

const API = 'https://api.hahow.in/api';

$id = $argv[1];

file_put_contents(
	"{$id}.json",
	json_encode(
		fetch($id)
	)
);

function fetch($id) {
	$href = API . "/courses/{$id}";
	$json = file_get_contents($href);
	$data = json_decode($json, true);

	return [
		'Name' => $data['title'],
		'Intro' => $data['metaDescription'],
		'Syllabus' => $data['description'],
		'Price' => $data['price'],
		'TeacherSN' => 1,
		'DomainSN' => 1,
		'ThumbnailSN' => 1
	];
}
