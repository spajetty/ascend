<?php
session_start();
session_unset();
session_destroy();

$qs = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
header("Location: /pages/auth/login.php" . $qs);
exit;
?>