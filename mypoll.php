<?php
ob_start();
 $page = 'my-poll';
 require_once 'dashboard.php';
 ob_end_flush();
?>