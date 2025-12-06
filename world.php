<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $country = isset($_GET['country']) ? $_GET['country'] : '';
    $lookup = isset($_GET['lookup']) ? $_GET['lookup'] : 'countries';
    
    $country = htmlspecialchars(strip_tags($country));
    
    if ($lookup === 'cities') {
        if (!empty($country)) {
            $query = "SELECT cities.name, cities.district, cities.population 
                      FROM cities 
                      JOIN countries ON cities.country_code = countries.code 
                      WHERE countries.name LIKE :country 
                      ORDER BY cities.population DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute([':country' => "%$country%"]);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($results) > 0) {
                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<tr><th>Name</th><th>District</th><th>Population</th></tr>';
                
                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['district']) . '</td>';
                    echo '<td>' . number_format($row['population']) . '</td>';
                    echo '</tr>';
                }
                
                echo '</table>';
            } else {
                echo '<p>No cities found for "' . $country . '".</p>';
            }
        } else {
            echo '<p>Please enter a country name to search for cities.</p>';
        }
    } else {
        if (!empty($country)) {
            $query = "SELECT name, continent, independence_year, head_of_state 
                      FROM countries 
                      WHERE name LIKE :country 
                      ORDER BY name";
            $stmt = $conn->prepare($query);
            $stmt->execute([':country' => "%$country%"]);
        } else {
            $query = "SELECT name, continent, independence_year, head_of_state 
                      FROM countries 
                      ORDER BY name";
            $stmt = $conn->query($query);
        }
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($results) > 0) {
            echo '<table border="1" cellpadding="5" cellspacing="0">';
            echo '<tr><th>Name</th><th>Continent</th><th>Independence Year</th><th>Head of State</th></tr>';
            
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
                echo '<td>' . htmlspecialchars($row['independence_year']) . '</td>';
                echo '<td>' . htmlspecialchars($row['head_of_state']) . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo '<p>No countries found for "' . $country . '".</p>';
        }
    }
    
} catch(PDOException $e) {
    echo '<p>Database error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>