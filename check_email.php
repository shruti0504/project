<?php
// Include PHPMailer libraries
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "menstrual_cycle";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current date
$current_date = date('Y-m-d');

// Query to get all cycles where email has not been sent yet
$sql = "SELECT * FROM cycles WHERE email_sent = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $next_period_date = $row['next_period_date'];
        $email = $row['email'];
        $username = $row['username'];//session username for subject

        // Calculate the difference in days
        $date_diff = (strtotime($next_period_date) - strtotime($current_date)) / (60 * 60 * 24);

        if ($date_diff == 2 || $date_diff < 0) {
            // Prepare email content
            
            $subject = "Hello $username, Your Period is Coming Soon!";
            $message = "Just a friendly reminder that your next period is expected on ".date('F j, Y', strtotime($next_period_date)) . ". Please prepare accordingly.
            <p>What you can do now:</p>
            <ul>
                <li>📅 Mark your calendar.</li>
                <li>💊 Stock up on any essentials you might need.</li>
                <li>🌿 Prepare some soothing teas or treats.</li>
                <li>💃 Plan some self-care time just for you.</li>
            </ul>
            <p>Remember, taking care of yourself is the best thing you can do. If you have any questions or need support, we're here for you!</p>
            <p>Stay well,<br>The Cycle Care Team 💗</p>";

            // Send email
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'shrutiprasad1750@gmail.com';  // Replace with your Gmail username
                $mail->Password = 'nogvhvxdlwfsadhg';  // Replace with your Gmail password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('shrutiprasad1750@gmail.com');  // Replace with your Gmail address
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();

                // Update the database to mark email as sent
                $update_sql = "UPDATE cycles SET email_sent = 1 WHERE id = " . $row['id'];
                $conn->query($update_sql);
            } catch (Exception $e) {
                echo "Error sending email: " . $mail->ErrorInfo;
            }
        }
    }
}

$conn->close();
?>
