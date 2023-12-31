<?php
/**
 * Source file path
 * This variable should be equal to your compare-source.php file path
 * @var string
 * example: http://localhost/compare-source.php
 */
$sourceFile = 'http://localhost/projects-unilever/myutip.com/trunk/compare-remote.php';

/**
 * Remote file path
 * This variable should be equal to your compare-remote.php file path
 * @var string
 * example: http://yourdomain/compare-remote.php
 */
$remoteFile = 'http://52.220.149.162:8082/compare-remote.php';

/**
 * Enter files and folders list to be scanned.
 * If you want to scan everything from root just put "." in here.
 * example:
 * css (a folder)
 * index.php (a file)
 */
define('SCAN_LIST', serialize(array(
    '.', // root
    // 'controllers', // root
)));

/**
 * Enter files and folders list to be ignored.
 * If you want to ignore some folders or files list down here.
 * example:
 * if you have added a "." (everything) to be scanned fron root of your project and
 * if you don't want to scan todo.txt file then add like below.
 * './todo.txt'
 * like I have already added some folders and files to be ignored.
 */
define('IGNORE_LIST', serialize(array(
    './compare',
    './compare-source.php',
    './compare-remote.php',
    '.public/uploads',
    './storage',
    './vendor',
    './public/backend/admin/default',
    './public/backend/admin/metronic',
    './frontend',
    './.svn',
    './.idea',
    './tests',
)));

/**
 * IGNORE_EVERYWHERE_LIST is a list where you can enter some files to be ignored from every children directries.
 * I have added some files and these files won't be scanned if exist in your project, being in root directory
 * or in children directries.
 */
define('IGNORE_EVERYWHERE_LIST', serialize(array(
    '.',
    '..',
    'cgi-bin',
    '.DS_Store',
    '.git',
    '.gitignore',
    'README.md',
)));

/**
 * Security Key
 * This key should be the same in local and remote config file otherwise you won't be able to upload/download files.
 */
define('KEY', 'JFKJD438KJL');

/**
 * Encryption Key
 * This key should be the same in local and remote config file otherwise you won't be able to upload/download files.
 * To generate different key use: bin2hex(openssl_random_pseudo_bytes(32))
 */
define('ENCRYPTION_KEY', 'b5bf4efbe4c1fa7361f5bd723dd95ed80b6fe27efd8e668f677b5f9a14170911');
