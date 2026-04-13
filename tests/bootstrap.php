<?php

// Test bootstrap — loaded BEFORE the application boots
// Define TESTING_MODE so bootstrap/app.php can disable CSRF

define('TESTING_MODE', true);

require __DIR__.'/../vendor/autoload.php';
