<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbox_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if message is set
if (!isset($_POST['message'])) {
    echo json_encode(['response' => "No message received", 'relatedQueries' => [], 'askFeedback' => false]);
    exit();
}

$message = $conn->real_escape_string($_POST['message']);

// Initialize response, related queries, and askFeedback flag
$response = "";
$relatedQueries = [];
$askFeedback = false;

// Check if the message is feedback-related
if (stripos($message, 'satisfied') !== false || stripos($message, 'thank you') !== false) {
    $response = "Thank you for using our service! Have a great day!";
} else if (stripos($message, 'more') !== false) {
    $response = "Please ask your next question from the related queries or type a new one.";
} else {
    // First, check if the message matches a related query
    $relatedQuerySql = "SELECT related_response FROM related_queries WHERE related_query LIKE '%$message%'";
    $relatedResult = $conn->query($relatedQuerySql);

    if ($relatedResult === false) {
        die("Error in SQL query: " . $conn->error);
    }

    if ($relatedResult->num_rows > 0) {
        $relatedRow = $relatedResult->fetch_assoc();
        $response = $relatedRow['related_response'];
        $askFeedback = true;
    } else {
        // If no related query matches, check for the main question
        $sql = "SELECT response FROM faqs WHERE question LIKE '%$message%'";
        $result = $conn->query($sql);

        if ($result === false) {
            die("Error in SQL query: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = $row['response'];
            $askFeedback = true;

            // Fetch related queries with detailed responses
            $relatedQuerySql = "SELECT related_query FROM related_queries WHERE question LIKE '%$message%'";
            $relatedResult = $conn->query($relatedQuerySql);

            if ($relatedResult === false) {
                die("Error in SQL query: " . $conn->error);
            }

            while ($relatedRow = $relatedResult->fetch_assoc()) {
                $relatedQueries[] = [
                    'query' => $relatedRow['related_query']
                ];
            }
        } else {
            $response = "I'm not sure about that. Could you please provide more details?";
        }
    }
}

// Prepare the response
header('Content-Type: application/json');
echo json_encode([
    'response' => $response,
    'relatedQueries' => $relatedQueries,
    'askFeedback' => $askFeedback
]);

$conn->close();

?>
