<?php
ob_start();
 $page = 'public';
 require_once 'dashboard.php';
 ob_end_flush();
?>