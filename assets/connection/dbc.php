<?
include $_SERVER['ROOT_PATH'].'/../quiz-master-creds/key.php';
include $_SERVER['ROOT_PATH'].'../quiz-master-creds/creds.php';
include $_SERVER['ROOT_PATH'].'assets/includes/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$baseURL='/quiz-master/';
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

