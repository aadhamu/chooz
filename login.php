<?php
ob_start();
session_start();

$page='login';
require_once 'components/sanitize.php';
require_once 'config.php';
require_once 'components/layout.php';
require_once 'components/login.php';
ob_end_flush();