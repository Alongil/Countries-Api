<?php
require 'functions.php';

$country_abbreviation = $_POST['data']['country'];
$zip_code = $_POST['data']['zipCode'];
if (empty($country_abbreviation) || empty($zip_code)) {
    http_response_code(400);
    exit();
}

try {
    $places = findPlaceByZipCode($country_abbreviation, $zip_code);
} catch (Exception $e) {
    http_response_code(500);
    exit();
}
if (sizeof($places) == 0) {
    http_response_code(404);
    exit();
}

echo json_encode($places);
