<?php

use lib\Config;

// DB Config
Config::write('db.host', ini_get('mysql.default.host'));
Config::write('db.port', '');
Config::write('db.basename', ini_get('mysql.default.user'));
Config::write('db.user', ini_get('mysql.default.user'));
Config::write('db.password', ini_get('mysql.default.pass'));

// Project Config
Config::write('path', $_SERVER['SERVER_NAME']);
