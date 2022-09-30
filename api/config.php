<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



# Basic configuration
const LOG = __DIR__ . '/app.log';
const SITE_NAME = 'exchange-crm';
const URL = 'http://localhost/figmashotstack/';
const LIBS = 'libs/';
const JSON = 'public/data/';
define('CSS_VERSION', date('l jS \of F Y h:i:s A'));
const PATH_ADMIN = 'panel/';
const PATH_USER = 'user';
const UPLOAD_IMAGE = 'build/images/';
const UPLOAD_DOC = 'build/doc/';

# Database
const DB_TYPE = 'mysql';
const DB_HOST = 'localhost';
const DB_NAME = 'frosty';
const DB_CHARSET = 'utf8mb4';
const DB_USER = 'root';
const DB_PASS = '';