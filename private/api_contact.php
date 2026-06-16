<?php

session_start();
if(!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] != TRUE) header("Location: ../public/index.php");

$apiKey = "3e680f5dbc343c0b4bae9c4d4b08ed48";
$name_movie= urlencode($_POST["nomMovie"]);
$Api_name = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query={$name_movie}";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $Api_name);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
$response1 = curl_exec($curl);
$data = json_decode($response1, true);

if ($data["total_results"] == 0 ) {
    echo "
    <div class='alert alert-danger m-0' role='alert'>
        Le film que vous avez marqué n'existe pas.
    </div>
    ";
} else {
    $get_id = $data['results'][0]['id'];
    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.themoviedb.org/3/movie/{$get_id}?language=fr",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzZTY4MGY1ZGJjMzQzYzBiNGJhZTljNGQ0YjA4ZWQ0OCIsIm5iZiI6MTc4MDk2MzkyMi45ODUsInN1YiI6IjZhMjc1YTUyMzAyN2M5OWNiZmY0ODBkYiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.BXfsXvkDLyRaYDPOtpMVptK6WROZqlo00olJV07K-YU",
        "accept: application/json"
    ],
    ]);

    $response2 = curl_exec($curl);
    $err = curl_error($curl);

    $data_fr = json_decode($response2, true);

    curl_close($curl);

    if ($err) {
    echo "Le film n'a pas pu être trouvé. Veuillez Reéssayer";
    }
}

?>