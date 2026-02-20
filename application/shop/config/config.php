<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load shared base config
require SHAREDPATH . 'config/config.php';

// Log settings (override only what we need here)
$config['log_threshold'] = 0; // 0=Disables, 1=Error, 2=Debug, 3=Info, 4=All
$config['log_path'] = ''; // default: application/shop/logs
