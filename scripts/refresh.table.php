<?php

require '../vendor/autoload.php';
require '../configs/production.config.php';

//$core = \lib\Core::getInstance();

ddd('kint loaded');
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
  `id` INT(11) UNSIGNED NOT NULL,
  `oeb` ENUM('O','E','B') NOT NULL DEFAULT 'B',
  `range_start` INT(6) UNSIGNED NOT NULL DEFAULT '0',
  `range_end` INT(6) UNSIGNED NOT NULL DEFAULT '0',
  `prefix_dir` CHAR(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `street_name` VARCHAR(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suffix_dir` CHAR(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `suffix_type` VARCHAR(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip_code` VARCHAR(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` VARCHAR(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip` INT(5) UNSIGNED ZEROFILL NOT NULL DEFAULT '0',
  `block_id` INT(11) DEFAULT NULL,
  `usage` CHAR(1) NOT NULL DEFAULT '',
  `status` TINYINT(1) NOT NULL DEFAULT '0',
  `precinct` INT(4) UNSIGNED ZEROFILL NOT NULL DEFAULT '0',
  `voters` INT(5) NOT NULL DEFAULT '0',
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
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
