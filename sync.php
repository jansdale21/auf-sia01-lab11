<?php
require_once 'vendor/autoload.php';

use Algolia\AlgoliaSearch\Api\SearchClient;

$host = "localhost";
$user = "root";
$pass = "";
$db   = "movie"; 

$algoliaAppId = "CSWSDEW39Q";
$algoliaWriteKey = "c2a11ea57eebc711be5b9fda17c6f4d8";
$indexName = "movies";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$client = SearchClient::create($algoliaAppId, $algoliaWriteKey);

$sql = "SELECT * FROM moviedb";
$result = $conn->query($sql);

$records = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['objectID'] = $row['id'];

        if (!empty($row['release_date'])) {
            $row['release_year'] = (int) date('Y', strtotime($row['release_date']));
        }

        if (!empty($row['genre'])) {
            $genres = array_map('trim', explode(',', $row['genre']));
            $genres = array_filter($genres, fn($value) => $value !== '');
            $row['genres'] = array_values($genres);
        }

        $records[] = $row;
    }
}

if (!empty($records)) {
    $client->saveObjects($indexName, $records);
    $client->setSettings($indexName, [
        'attributesForFaceting' => ['genres', 'release_year'],
        'searchableAttributes' => ['title', 'overview', 'genre', 'release_year'],
        'replicas' => [
            'movies_release_year_desc',
            'movies_release_year_asc',
            'movies_rating_desc',
        ],
    ]);

    $client->setSettings('movies_release_year_desc', [
        'attributesForFaceting' => ['genres', 'release_year'],
        'searchableAttributes' => ['title', 'overview', 'genre', 'release_year'],
        'customRanking' => ['desc(release_year)'],
    ]);
    $client->setSettings('movies_release_year_asc', [
        'attributesForFaceting' => ['genres', 'release_year'],
        'searchableAttributes' => ['title', 'overview', 'genre', 'release_year'],
        'customRanking' => ['asc(release_year)'],
    ]);
    $client->setSettings('movies_rating_desc', [
        'attributesForFaceting' => ['genres', 'release_year'],
        'searchableAttributes' => ['title', 'overview', 'genre', 'release_year'],
        'customRanking' => ['desc(vote_average)'],
    ]);

    echo "Data successfully synced to Algolia!";
} else {
    echo "No records found.";
}

$conn->close();
?>