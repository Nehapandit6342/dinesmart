<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure 'bills' directory exists
    if (!is_dir('bills')) {
        mkdir('bills', 0777, true);
    }

    $token = $_POST['token'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $items = $_POST['items']; // JSON string
    $total = $_POST['total'];
    $pdfPath = "bills/{$token}.pdf";

    // Save uploaded PDF file
    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $pdfPath)) {
        // Save bill data to database
        $conn = new mysqli("localhost", "root", "", "dinesmart");
        if ($conn->connect_error) {
            http_response_code(500);
            echo "Database connection failed.";
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO bills (token, customer_name, phone, items, total_amount, bill_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssd", $token, $name, $phone, $items, $total);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo "Bill saved successfully.";
    } else {
        http_response_code(500);
        echo "Failed to upload PDF.";
    }
}
?>
