<?php
header("Content-Type: application/json");

$conn = mysqli_connect(
    "sql300.infinityfree.com",
    "if0_41010246",
    "exSCcIKxum",
    "if0_41010246_weather"
);

if (!$conn) {
    echo json_encode(["error" => mysqli_connect_error()]);
    exit;
}


// Create DB & table
mysqli_select_db($conn, "if0_41010246_weather");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS weather_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100),
    temperature FLOAT,
    humidity FLOAT,
    pressure FLOAT,
    wind FLOAT,
    wind_direction FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// City
$city = isset($_GET['q']) ? trim($_GET['q']) : "Kalaiya";
$citySafe = mysqli_real_escape_string($conn, $city);

// API
$apiKey = "ba6ffb002bbe60b898d4f03be29c129a";
$url = "https://api.openweathermap.org/data/2.5/weather?q=$citySafe&appid=$apiKey&units=metric";

$response = @file_get_contents($url);
if ($response === FALSE) {
    echo json_encode(["cod" => 404, "message" => "City not found"]);
    exit;
}

$data = json_decode($response, true);
if (!isset($data["main"])) {
    echo json_encode(["cod" => 404, "message" => "Invalid data"]);
    exit;
}

// Extract
$temp = $data["main"]["temp"];
$humidity = $data["main"]["humidity"];
$pressure = $data["main"]["pressure"];
$wind = $data["wind"]["speed"];
$direction = $data["wind"]["deg"];

// Insert
$insertSql = "
    INSERT INTO weather_data
    (city, temperature, humidity, pressure, wind, wind_direction)
    VALUES
    ('$citySafe', '$temp', '$humidity', '$pressure', '$wind', '$direction')
";

if (!mysqli_query($conn, $insertSql)) {
    echo json_encode([
        "cod" => 500,
        "mysql_error" => mysqli_error($conn)
    ]);
    exit;
}

// Return API JSON to JS
echo json_encode($data);
?>
