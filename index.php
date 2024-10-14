<?php 
// PHP environment and init vars
error_reporting(E_ALL);
ini_set('display_errors', '1');

// init error reporting array
$exceptions = array();

// Class files
require_once('TagReader.php');
require_once('Hierarchy.php');
require_once('Collection.php');
require_once('Member.php');

// Open data file and make tag sets. Example #1
/*$sys_name = 'IMDB Top 1,000';
$rows = file('imdb_top_1000.csv');
$header_row = array_shift($rows);
$tag_sets = array();
foreach ( $rows as $row ) {
	$fields = str_getcsv($row, ",", "\"");
	$key = strval($fields[1]);
	$name = strval($fields[1]);
	#$uri = 'urn:' . urlencode($key);
	$uri = 'https://en.wikipedia.org/wiki/' . urlencode(strtr($key, ' ', '_'));
	$delimited_tags = $fields[9] . chr(31) . $fields[10] . chr(31) . $fields[11] . chr(31) . $fields[12] . chr(31) . $fields[13];
	$tag_sets[$key]['name'] = $name;
	$tag_sets[$key]['uri'] = $uri;
	$tag_sets[$key]['delimited_tags'] = $delimited_tags;
}*/

// Open data file and make tag sets. Example #2
$sys_name = 'Rolling Stone Top 500 Albums';
$rows = file('rs_500_albums.csv');
$header_row = array_shift($rows);
$tag_sets = array();
foreach ( $rows as $row ) {
	$fields = str_getcsv($row, ",", "\"");
	$descriptors = array();
	if(!empty($fields[4])){
		$genres = explode(', ', $fields[4]);
		$descriptors = array_merge($descriptors, $genres);
	}
	if(!empty($fields[5])){
		$subgenres = explode(', ', $fields[5]);
		$descriptors = array_merge($descriptors, $subgenres);
	}
	$key = '#' . strval($fields[0]) . '. ' . $fields[2] . '. (' . $fields[3] . ')';
	$name = '#' . strval($fields[0]) . '. ' . $fields[2] . '. (' . $fields[3] . ')';
	$uri = 'https://en.wikipedia.org/w/index.php?search=' . urlencode($fields[2] . '. (' . $fields[3] . ')');
	$delimited_tags = implode(chr(31), $descriptors);
	$tag_sets[$key]['name'] = $name;
	$tag_sets[$key]['uri'] = $uri;
	$tag_sets[$key]['delimited_tags'] = $delimited_tags;
}

// create TagReader instance and convert tags to paths
try {
	$t = new TagReader($tag_sets);
} catch (Exception $e ) {
	$exceptions[] = $e->getMessage();
}

// create instance of Hierarchy
try {
	$h = Hierarchy::create($sys_name);
} catch (Exception $e) {
	$exceptions[] = $e->getMessage();
}

// create and add Members to Hierarchy
foreach ( $t->getTagSets() as $key => $set ) {
	try {
		$key = strval($key);
		$name = strval($set['name']);
		$uri = strval($set['uri']);
		$path = strval($set['tag_path']);
		$m = Member::create($key, $name, $uri, $path);
	} catch (Exception $e) {
		$exceptions[] = $e->getMessage();
	}
	try {
		$h->addMember($m);
	} catch (Exception $e) {
		$exceptions[] = $e->getMessage();
	}
}

// use HTML display methods of Hierarchy instance
$html = $h->buildHTMLList();

// load HTML template
include('template.php');
?>