<?php
ob_start();
session_start();
require_once 'components/sanitize.php';
require_once 'config.php';

$page='signup';
require_once 'components/layout.php';
require_once 'components/signup.php';
ob_end_flush();