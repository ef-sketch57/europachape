<?php
require_once __DIR__ . '/../includes/auth.php';
start_secure_session();
admin_logout();
redirect('index.php');
