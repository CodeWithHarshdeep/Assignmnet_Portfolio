<?php
// Define the file where emails will be stored
$file = 'contacts.txt';

// Function to send email notification
function send_email_notification($name, $email, $message) {
    $to = "your-email@example.com"; // Change this to your email
    $subject = "New Message from Portfolio";
    $headers = "From: portfolio@example.com" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $body = "You received a new message from $name <$email>:\n\n$message";
    
    mail($to, $subject, $body, $headers);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // Check if email already exists in the file
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if (in_array($email, $emails)) {
        echo "<script>alert('You have already contacted us with this email!');</script>";
    } else {
        // Save email to the text file
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
        
        // Send notification email
        send_email_notification($name, $email, $message);

        // Save the user's message to a file
        $log = fopen("messages.txt", "a");
        fwrite($log, "From: $name <$email>\nMessage: $message\n\n");
        fclose($log);

        // Success alert and redirect to the portfolio
        echo "<script>alert('Thank you for your message!'); window.location.href = 'index.html';</script>";
    }
} else {
    // Redirect to the portfolio if the form wasn't submitted correctly
    header("Location: index.html");
}
?>
