<?php

require_once 'module.php';

const API = 'https://api.hahow.in/api';

echo 'Amassing...' . PHP_EOL;

foreach (array_slice($argv, 1) as $course) {
	echo fetch($course) . PHP_EOL;
}

function fetch($course) {
	$href = API . "/courses/{$course}";
	$json = file_get_contents($href);
	$data = json_decode($json, true);

	$user = write('User', [
		'Username' => $username = $data['owner']['username'] ?? $data['owner']['_id'],
		null,
		'FirstName' => '',
		'LastName' => '',
		'Nickname' => $data['owner']['name'],
		'E-mail' => $username . '@example.edu',
		'JobTitle' => $data['owner']['skills'] ?? '',
		// 'Intro' => $data['owner']['description'] ?? '',
		'Resume' => $data['owner']['metaDescription'] ?? '',
		'WhenQualified' => date('Y-m-d H:i:s', time()),
		'ApproverSN' => 0
	]);
	$domain = write('Domain', [
		'Identifier' => $data['group']['subGroup']['uniquename'],
		null,
		'Name' => $data['group']['subGroup']['title'],
		'Intro' => '',
		'ParentSN' => write('Domain', [
			'Identifier' => $data['group']['uniquename'],
			null,
			'Name' => $data['group']['title'],
			'Intro' => '',
			'CreatorSN' => 0
		]),
		'CreatorSN' => 0
	]);
	$daily = write('File', [
		'ContentHash' => hash_file(
			'sha256',
			$link = end($data['video']['videos'])['link']
		),
		null,
		'Filename' => $link,
		'Extension' => '',
		'ContentType' => '',
		'UploaderSN' => $user
	]);
	$thumbnail = write('File', [
		'ContentHash' => hash_file(
			'sha256',
			$link = $data['coverImage']['url']
		),
		null,
		'Filename' => $link,
		'Extension' => '',
		'ContentType' => '',
		'UploaderSN' => $user
	]);

	return write('Course', [
		'Identifier' => $data['uniquename'],
		null,
		'Name' => $data['title'],
		'Intro' => $data['metaDescription'],
		'Syllabus' => $data['description'],
		'Price' => $data['price'],
		'WhenCreated' => date('Y-m-d H:i:s', strtotime($data['createdAt'] ?? time())),
		'WhenApproved' => date('Y-m-d H:i:s', strtotime($data['incubateTime'] ?? time())),
		'WhenLaunched' => !isset($data['publishTime'])
			? null
			: date('Y-m-d H:i:s', strtotime($data['publishTime'] ?? time())),
		'TeacherSN' => $user,
		'DomainSN' => $domain,
		'DailySN' => $daily,
		'ThumbnailSN' => $thumbnail,
		'ApproverSN' => 0
	]);
}
