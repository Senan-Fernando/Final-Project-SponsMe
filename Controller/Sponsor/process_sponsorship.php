<?php
session_start();
include '../../Model/db.php';

if (!isset($_SESSION['userEmail'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo "Error: Not logged in";
    exit();
}

// Validate input
if (!isset($_POST['request_id']) || !isset($_POST['amount'])) {
    header('HTTP/1.1 400 Bad Request');
    echo "Error: Missing required parameters";
    exit();
}

$request_id = $_POST['request_id'];
$amount = floatval($_POST['amount']);
$notes = isset($_POST['notes']) ? $_POST['notes'] : '';

// Validate amount
if ($amount <= 0) {
    header('HTTP/1.1 400 Bad Request');
    echo "Error: Invalid amount value";
    exit();
}

// Get the sponsor_id based on the logged-in user's email
$userEmail = $_SESSION['userEmail'];
$sponsorQuery = "SELECT id, emp_id, unit, company_registration_code FROM sponsors WHERE email = ?";
$stmt = $conn->prepare($sponsorQuery);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $sponsorRow = $result->fetch_assoc();
    $sponsor_id = $sponsorRow['id'];
    $emp_id = $sponsorRow['emp_id'];
    $unit = $sponsorRow['unit'];
    $company_registration_code = $sponsorRow['company_registration_code'];

    // Check if this request belongs to the logged-in sponsor and is in accepted status
    $checkQuery = "SELECT sr.id, sr.event_topic 
                   FROM sponsorship_requests sr 
                   WHERE sr.id = ? AND sr.sponsor_id = ? AND sr.status = 'accepted'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $request_id, $sponsor_id);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult && $checkResult->num_rows > 0) {
        $requestData = $checkResult->fetch_assoc();
        $event_topic = $requestData['event_topic'];

        // Process file upload
        $document_path = '';
        $upload_error = false;

        if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
            $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            $file_type = $_FILES['document']['type'];
            $file_size = $_FILES['document']['size'];
            $file_tmp = $_FILES['document']['tmp_name'];
            $file_name = time() . '_' . $_FILES['document']['name'];

            // Check file type
            if (!in_array($file_type, $allowed_types)) {
                header('HTTP/1.1 400 Bad Request');
                echo "Error: Invalid file type. Only PDF and image files are allowed.";
                exit();
            }

            if ($file_size > 5 * 1024 * 1024) {
                header('HTTP/1.1 400 Bad Request');
                echo "Error: File too large. Maximum size is 5MB.";
                exit();
            }

            $upload_dir = '../../uploads/sponsorship_docs/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $document_path = $upload_dir . $file_name;

            if (!move_uploaded_file($file_tmp, $document_path)) {
                $upload_error = true;
                header('HTTP/1.1 500 Internal Server Error');
                echo "Error: Failed to upload file.";
                exit();
            }

            // Store only the relative path in the database
            $document_path = 'uploads/sponsorship_docs/' . $file_name;
        } else {
            header('HTTP/1.1 400 Bad Request');
            echo "Error: Document file is required.";
            exit();
        }

        if (!$upload_error) {
            
            $conn->begin_transaction();

            try {
                $createTableQuery = "CREATE TABLE IF NOT EXISTS sponsorship_details (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    request_id INT NOT NULL,
                    sponsor_id INT NOT NULL,
                    amount DECIMAL(10,2) NOT NULL,
                    document_path VARCHAR(255) NOT NULL,
                    notes TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (request_id) REFERENCES sponsorship_requests(id),
                    FOREIGN KEY (sponsor_id) REFERENCES sponsors(id)
                )";

                $conn->query($createTableQuery);

                $insertQuery = "INSERT INTO sponsorship_details (request_id, sponsor_id, amount, document_path, notes) 
                                VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("iidss", $request_id, $sponsor_id, $amount, $document_path, $notes);
                $stmt->execute();
                $sponsorship_details_id = $conn->insert_id;

                $current_date = date('Y-m-d');
                $description = "Sponsorship for event: " . $event_topic;
                if (!empty($notes)) {
                    $description .= " - " . $notes;
                }

                $budgetQuery = "INSERT INTO sponsorship_budget 
                (sponsor_id, emp_id, unit, company_registration_code, amount, description, 
                 transaction_type, transaction_date) 
                VALUES (?, ?, ?, ?, ?, ?, 'Expense', ?)";
                $budgetStmt = $conn->prepare($budgetQuery);
                $budgetStmt->bind_param(
                    "isssdss",
                    $sponsor_id,
                    $emp_id,
                    $unit,
                    $company_registration_code,
                    $amount,
                    $description,
                    $current_date
                );
                $budgetStmt->execute();

                
                $conn->commit();

                
                header('Location: ../../Views/Event Sponsor/Request.php?success=1&message=Sponsorship details have been successfully submitted and budget updated.');
                exit();
            } catch (Exception $e) {
                
                $conn->rollback();
                header('HTTP/1.1 500 Internal Server Error');
                echo "Error: " . $e->getMessage();
                exit();
            }
        }
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo "Error: You do not have permission to update this request or the request is not in accepted status.";
    }
} else {
    header('HTTP/1.1 404 Not Found');
    echo "Error: Sponsor not found for this email.";
}

$conn->close();
