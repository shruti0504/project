<?php
// process_symptoms.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : [];

    if (empty($symptoms)) {
        echo json_encode(['error' => 'No symptoms selected']);
        exit;
    }

    $pdo = new PDO('mysql:host=localhost;dbname=symptom_checker', 'root', '');

    // Prepare SQL query with placeholders for symptoms
    $placeholders = implode(',', array_fill(0, count($symptoms), '?'));

    // Select conditions based on symptoms
    $stmt = $pdo->prepare("
        SELECT conditions.name, conditions.description
        FROM conditions
        WHERE conditions.id IN (
            SELECT condition_id FROM symptom_condition WHERE symptom_id IN (
                SELECT id FROM symptoms WHERE name IN ($placeholders)
            )
        )
    ");
    $stmt->execute($symptoms);
    $conditions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($conditions);
}
?>
