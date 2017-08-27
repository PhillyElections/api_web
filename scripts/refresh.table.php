<?php
require '../vendor/autoload.php';
dd('kint loaded');
// Parameters
if ($argv[1]) {
    $file = $argv[1];
} else {
    die("Please provide file and table names\n");
}
if ($argv[2]) {
    $table = $argv[2];
} else {
    die("Please provide a table name\n");
}

/********************************************************************************/
// Get the first row to create the column headings

if (! $fp = fopen($file, 'r')) {
    die("$file not found.");
}

$frow = fgetcsv($fp);

$ccount = 0;
foreach ($frow as $column) {
    $ccount++;
    if ($columns) {
        $columns .= ', ';
    }
    $columns .= "$column varchar(250)";
}

$create = "
DROP TABLE IF EXISTS `block_range`;
CREATE TABLE `block_range` (
  `id` int(11) UNSIGNED NOT NULL,
  `house_range_start` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `house_range_end` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `prefix_dir` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `street_name` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_dir` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `block_id` int(11) DEFAULT NULL,
  `precinct_split` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=Aria DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

$qry = $dbcon->prepare($create);
$qry->execute();

/********************************************************************************/
// Import the data into the newly created table.

import = <<<_IMPORT
mysql --user=$user --password=$pass $dbName --execute="LOAD DATA LOCAL INFILE '$dest' INTO TABLE jos_rt_imports FIELDS TERMINATED BY '$delim' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' $ignore ($sFields) SET $lField = TRIM(TRAILING '\r' FROM @var);"
_IMPORT;

system($import);


$qry = $dbcon->prepare("load data infile '$file' into table $table fields terminated by ',' ignore 1 lines");
$qry->execute();
