<?php
// Include Composer's autoloader
require 'vendor/autoload.php';  // Automatically load all PhpSpreadsheet classes

use PhpOffice\PhpSpreadsheet\IOFactory;

// Include the session file to access the existing database connection
include 'includes/session.php';
include 'includes/conn.php';

// Function to map position text to its corresponding numeric value
function getPositionNumber($positionText) {
    $positions = [
        'faculty' => 0,
        'cos faculty' => 1,
        'external faculty' => 2,
        'non-teaching staff' => 3
    ];

    // Convert input to lowercase and check if it exists in the positions array
    $positionText = strtolower(trim($positionText));
    return isset($positions[$positionText]) ? $positions[$positionText] : null;
}

// Function to generate a unique faculty ID
function generateFacultyID($conn) {
    // Retrieve the last faculty_id
    $query = "SELECT faculty_id FROM faculty ORDER BY faculty_id DESC LIMIT 1";
    $result = $conn->query($query);
    $lastID = ($result->num_rows > 0) ? $result->fetch_assoc()['faculty_id'] : null;

    if ($lastID) {
        // Remove the 'F' prefix and increment the numeric part
        $numericPart = (int)substr($lastID, 1);
        $newID = 'F' . str_pad($numericPart + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // Start from F0001 if no ID exists
        $newID = 'F0001';
    }

    return $newID;
}

if (isset($_POST['import']) && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    if ($file) {
        try {
            // Load the Excel file using PhpSpreadsheet
            $spreadsheet = IOFactory::load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            global $conn;

            $successCount = 0; // Counter for successful rows
            $errors = []; // Array to store error messages

            foreach ($sheetData as $key => $row) {
                // Skip the header row and rows that are entirely empty
                if ($key == 0 || empty(array_filter($row))) continue;

                // Extract faculty data from the row
                $positionText = trim($row[0]); // Position
                $lastName = trim($row[1]); // Last Name
                $firstName = trim($row[2]); // First Name
                $middleName = trim($row[3]); // Middle Name

                // Skip rows with missing critical data (Position, Last Name, First Name)
                if (!$lastName || !$firstName || !$positionText) {
                    $missingFields = [];

                    if (!$lastName) {
                        $missingFields[] = "Last Name";
                    }
                    if (!$firstName) {
                        $missingFields[] = "First Name";
                    }
                    if (!$positionText) {
                        $missingFields[] = "Position";
                    }

                    $errors[] = "Row $key: Missing required data (" . implode(", ", $missingFields) . ").";
                    continue;
                }

                // Get the position number from the position text
                $position = getPositionNumber($positionText);

                if ($position === null) {
                    $errors[] = "Row $key: Invalid position '$positionText'. Valid positions are 'Faculty', 'COS Faculty', 'External Faculty', 'Non-teaching Staff'.";
                    continue;
                }

                // Generate faculty ID
                $faculty_id = generateFacultyID($conn);

                // Insert valid row into the database
                $stmt = $conn->prepare("
                    INSERT INTO faculty (faculty_id, firstname, middlename, lastname, position, created_on) 
                    VALUES (?, ?, ?, ?, ?, CURDATE())
                ");
                $stmt->bind_param('sssss', $faculty_id, $firstName, $middleName, $lastName, $position);

                if ($stmt->execute()) {
                    $successCount++;
                } else {
                    $errors[] = "Row $key: Failed to insert faculty '$firstName $lastName'.";
                }
            }

            // Set success and error messages
            if ($successCount > 0) {
                $_SESSION['success'] = "$successCount faculty member(s) imported successfully!";
            }
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
            }

            header('Location: faculty.php');
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header('Location: faculty.php');
        }
    } else {
        $_SESSION['error'] = "No file selected.";
        header('Location: faculty.php');
    }
} else {
    $_SESSION['error'] = 'Fill up the add form first.';
    header('Location: faculty.php');
}
exit();
?>
