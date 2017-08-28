<?php

require '../vendor/autoload.php';
require '../configs/production.config.php';

//$core = \lib\Core::getInstance();

d('kint loaded');
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

$drop = 'DROP TABLE IF EXISTS `block_range`';

$create = "
CREATE TABLE `block_range` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `oeb` enum('O','E','B') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B',
 `range_start` int(6) unsigned NOT NULL DEFAULT '0',
 `range_end` int(6) unsigned NOT NULL DEFAULT '0',
 `prefix_dir` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `street_name` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `suffix_dir` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `suffix_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `city` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `zip` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
 `block_id` int(11) DEFAULT NULL,
 `usage` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `status` tinyint(1) NOT NULL DEFAULT '0',
 `precinct` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
 `voters` int(5) NOT NULL DEFAULT '0',
 `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`id`),
 KEY `br_oeb` (`oeb`),
 KEY `br_range_start` (`range_start`),
 KEY `br_range_end` (`range_end`),
 KEY `br_prefix_dir` (`prefix_dir`),
 KEY `br_street_name` (`street_name`)
) ENGINE=Aria DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

$qry = $core->dbh->prepare($delete);
$qry->execute();

$qry = $core->dbh->prepare($create);
$qry->execute();

/********************************************************************************/
// Import the data into the newly created table.

$import = <<<_IMPORT
mysql --user=$user --password=$pass $dbName --execute="LOAD DATA LOCAL INFILE '$dest' INTO TABLE jos_rt_imports FIELDS TERMINATED BY '$delim' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' $ignore ($sFields) SET $lField = TRIM(TRAILING '\r' FROM @var);"
_IMPORT;

system($import);

$qry = $dbcon->prepare("load data infile '$file' into table $table fields terminated by ',' ignore 1 lines");
$qry->execute();
