<?php
// Include Composer's autoloader
require 'vendor/autoload.php';  // Automatically load all PhpSpreadsheet classes

use PhpOffice\PhpSpreadsheet\IOFactory;

// Include the session file to access the existing database connection
include 'includes/session.php';
include 'includes/conn.php';

// Define a function to map program ID by title or code
function getProgramIdByTitleOrCode($input, $conn) {
    $stmt = $conn->prepare("
        SELECT id 
        FROM program 
        WHERE LOWER(REPLACE(TRIM(title), ' ', '')) = LOWER(REPLACE(TRIM(?), ' ', ''))
           OR LOWER(REPLACE(TRIM(code), ' ', '')) = LOWER(REPLACE(TRIM(?), ' ', '')) 
        LIMIT 1
    ");
    $stmt->bind_param('ss', $input, $input);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result ? $result['id'] : null; // Return the program ID, or null if not found
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
            
                // Extract student data from the row
                $studentId = trim($row[0]); // Student ID
                $programInput = trim($row[1]); // Program title or code
                $lastName = trim($row[2]); // Last Name
                $firstName = trim($row[3]); // First Name
                $middleName = trim($row[4]); // Middle Name
            
                // Skip rows with missing critical data (Student ID, Program, Last Name, or First Name)
                if (!$studentId || !$programInput || !$lastName || !$firstName) {
                    $missingFields = [];

                    if (!$studentId) {
                        $missingFields[] = "Student ID";
                    }
                    if (!$programInput) {
                        $missingFields[] = "Program";
                    }
                    if (!$lastName) {
                        $missingFields[] = "Last Name";
                    }
                    if (!$firstName) {
                        $missingFields[] = "First Name";
                    }

                    $errors[] = "Row $key: Missing required data (" . implode(", ", $missingFields) . ").";
                    continue;
                }
            
                // Check if student ID already exists
                $checkStudent = $conn->prepare("SELECT 1 FROM students WHERE student_id = ?");
                $checkStudent->bind_param('s', $studentId);
                $checkStudent->execute();
                $studentExists = $checkStudent->get_result()->num_rows > 0;
            
                if ($studentExists) {
                    $errors[] = "Row $key: Student ID '$studentId' already exists.";
                    continue;
                }
            
                // Get program ID from the program table (by title or code)
                $programId = getProgramIdByTitleOrCode($programInput, $conn);
            
                if (!$programId) {
                    $errors[] = "Row $key: Program '$programInput' not found in the database.";
                    continue;
                }
            
                // Insert valid row into the database
                $stmt = $conn->prepare("
                    INSERT INTO students (student_id, program_id, lastname, firstname, middlename, created_on) 
                    VALUES (?, ?, ?, ?, ?, CURDATE())
                ");
                $stmt->bind_param('sssss', $studentId, $programId, $lastName, $firstName, $middleName);
            
                if ($stmt->execute()) {
                    $successCount++;
                } else {
                    $errors[] = "Row $key: Failed to insert student ID '$studentId'.";
                }
            }            

            // Set success and error messages
            if ($successCount > 0) {
                $_SESSION['success'] = "$successCount student(s) imported successfully!";
            }
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
            }

            header('Location: student.php');
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header('Location: student.php');
        }
    } else {
        $_SESSION['error'] = "No file selected.";
        header('Location: student.php');
    }
} else {
    $_SESSION['error'] = 'Fill up the add form first.';
    header('Location: student.php');
}
exit();
