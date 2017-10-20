<?php

# cleanup
if (is_file('movies.txt')) unlink('movies.txt');
if (is_file('actors.txt')) unlink('actors.txt');
if (is_file('movie-actors.txt')) unlink('movie-actors.txt');

# read csv into an array
$csv = array_map('str_getcsv', file('tmdb_5000_credits.csv'));

$moviesFile = fopen('movies.txt', 'ab');
$actorsFile = fopen('actors.txt', 'ab');
$movieActorsFile = fopen('movie-actors.txt', 'ab');

$actorsArray = [];

foreach ($csv as $row) {
	$movieId = $row[0];
	$movieName = $row[1];
	fwrite($moviesFile, $movieId . '|' . rtrim($movieName) . PHP_EOL);
	
	# just skip the dirty data
	if (!($actors = json_decode($row[2], true))) continue;
	
	foreach ($actors as $actor) {
		$actorId = $actor['id'];
		$actorName = $actor['name'];
		
		if (!in_array($actorId, $actorsArray)) {
			$actorsArray[] = $actorId;
			fwrite($actorsFile, $actorId . '|' . rtrim($actorName) . PHP_EOL);
		}
		
		fwrite($movieActorsFile, $movieId . '|' . $actorId . PHP_EOL);
	}
}

fclose($moviesFile);
fclose($actorsFile);
fclose($movieActorsFile);