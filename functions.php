<?php
require 'db_connection.php';

// checking if zip code exsists in db, if not calling the getInfoFromApi function
function findPlaceByZipCode($country_abbreviation, $zip_code)
{
    global $conn;
    // preventing sql injections
    $escaped_zip_code = mysqli_real_escape_string($conn, $zip_code);
    $escaped_country_abbreviation = mysqli_real_escape_string($conn,$country_abbreviation);
    $sql = "SELECT country, zip_id FROM zip_codes where zip_code = '$escaped_zip_code' AND country_abbreviation = '$escaped_country_abbreviation'";
//     if the connection is corrupted result will return false
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $error = mysqli_error($conn);
        error_log($error);
        throw new Exception("DB error");
    }
    // if zip codes exsists in the db, fetching returning all the places matching the zip code
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $zip_id = $row["zip_id"];
        $places = getPlaces($zip_id);
        return $places;
    } else {
        $zip_id = getDataFromApi($escaped_country_abbreviation,$escaped_zip_code);
        // returning an empty array if zip code doesnt exsists
        if ($zip_id == -1) {
            return [];
        }
        // handle error if there is an error in db
        if ($zip_id == -2) {
            throw new Exception("DB error");
        }
        return getPlaces($zip_id);
    }
}
// getting all the places from db matching the zip code parameter
function getPlaces($zip_id)
{
    global $conn;
    $sql = "SELECT place_name, latitude, longtitude FROM places where zip_id = '$zip_id'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $error = mysqli_error($conn);
        error_log($error);
        throw new Exception("DB error");
    }
    while ($places = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
        return $places;
    }
}

// Getting response from api, calling the insert functions and returning response from api
function getDataFromApi($country_abbreviation, $zip_code)
{
    // function gile_get_contents fails if 404 status return, and json respnse will get null
    $json_response = @file_get_contents(
        'http://api.zippopotam.us/' . $country_abbreviation . '/' . $zip_code
    );
    if (!$json_response) {
        return -1;
    }
    global $conn;
    $arr = json_decode($json_response, true);
    $zip_code = $arr["post code"];
    $country_name = $arr["country"];
    $country_abbreviation = $arr["country abbreviation"];
    $zip_code_id = insertZip($zip_code, $country_name, $country_abbreviation);
    if (!$zip_code_id) {
        return -2;
    }
    $arr_places = $arr["places"];
    $number_of_places = count($arr_places);
    // in case several places have the same zip code, inserting alll to DB
    for ($i = 0; $i < $number_of_places; $i++) {
        $place_name = $arr_places[$i]["place name"];
        $latitude = $arr_places[$i]["latitude"];
        $longtitude = $arr_places[$i]["longitude"];
        $response = insertPlaces(
            $zip_code_id,
            $place_name,
            $latitude,
            $longtitude
        );
        if (!$response) {
            return -2;
        }
    }
    return $zip_code_id;
}

// inserting the zip code into db
function insertZip($zip_code, $country_name, $country_abbreviation)
{
    global $conn;
    $sql_Insert_Zip = "INSERT INTO zip_codes(zip_code, country, country_abbreviation) VALUES ('$zip_code', '$country_name', '$country_abbreviation');";
    if (mysqli_query($conn, $sql_Insert_Zip)) {
        $zip_code_id = mysqli_insert_id($conn);
        return $zip_code_id;
    } else {
        $error = mysqli_error($conn);
        error_log($error);
        return false;
    }
}

// inserting the places db
function insertPlaces($zip_code_id, $place_name, $latitude, $longtitude)
{
    global $conn;
    $sql_Insert_Places = "INSERT INTO places(place_name, latitude, longtitude, zip_id) VALUES ('$place_name', '$latitude', '$longtitude', '$zip_code_id');";
    if (mysqli_query($conn, $sql_Insert_Places)) {
        return true;
    } else {
        $error = mysqli_error($conn);
        // writing the error to log file
        error_log($error);
        return false;
    }
}
