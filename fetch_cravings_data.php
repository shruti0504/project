<?php
session_start();
header('Content-Type: application/json');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "symptom_checker");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['username'])) {
    echo json_encode([]);
    exit();
}

$user = $_SESSION['username'];
// Fetch cravings data aggregated by type and month
$sql = "
    SELECT 
        cravings,
        MONTHNAME(created_at) as month,
        COUNT(*) as count
    FROM user_symptoms WHERE username='$user'
    GROUP BY cravings, MONTH(created_at)
    ORDER BY MONTH(created_at)
";

$result = $conn->query($sql);

$data = [];

// Organize the data by cravings and concatenate months
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $craving = $row['cravings'];
        $month = $row['month'];
        $count = $row['count'];

        if (!isset($data[$craving])) {
            $data[$craving] = [
                'craving' => $craving,
                'months' => [],
                'count' => 0,
            ];
        }

        $data[$craving]['months'][] = $month;
        $data[$craving]['count'] += (int)$count;
    }
}

// Reformat the data to include the months as a string
$formattedData = [];
foreach ($data as $cravingData) {
    $formattedData[] = [
        'craving' => $cravingData['craving'],
        'count' => $cravingData['count'],
        'months' => implode(', ', $cravingData['months'])
    ];
}

$conn->close();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($formattedData);
?>
