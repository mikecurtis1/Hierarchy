<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title><?php echo $sys_name; ?></title>
<link rel="stylesheet" type="text/css" href="main.css" media="screen" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<h1><?php echo $sys_name; ?></h1>
<!-- Examine tag reader parameters -->
<pre>
<?php echo 'Total tags: ' . count($t->getTagRanks()) . "\n"; ?>
<?php echo 'Top tag percentage: ' . $t->getTagRankPercentages()[1] . "\n"; ?>
<?php 
#print_r($t->getTagCounts());
#print_r($t->getTagRanks());
#print_r($t->getTagRankPercentages());
?>
</pre>
<ul>
<?php echo $html; ?>
</ul>
</body>
</html>
