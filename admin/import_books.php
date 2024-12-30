<?php
// Include Composer's autoloader
require 'vendor/autoload.php'; // Automatically load all PhpSpreadsheet classes

use PhpOffice\PhpSpreadsheet\IOFactory;

// Include the session and connection files
include 'includes/session.php';
include 'includes/conn.php';

// Function to get category ID by category name
function getCategoryIDByName($categoryName, $conn) {
    $stmt = $conn->prepare("SELECT id FROM category WHERE name = ? LIMIT 1");
    $stmt->bind_param('s', $categoryName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result ? $result['id'] : null;
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

                // Extract book data from the row
                $categoryNo = trim($row[0]); // Cat. No.
                $categoryName = trim($row[1]); // Category Name
                $accessionNo = trim($row[2]); // Accession No.
                $volume = trim($row[3]); // Volume
                $title = trim($row[4]); // Title
                $author = trim($row[5]); // Author
                $publisher = trim($row[6]); // Publisher
                $publishDate = trim($row[7]); // Publish Date

                // Skip rows with missing critical data
                if (!$categoryNo || !$categoryName || !$accessionNo || !$title || !$author) {
                    $missingFields = [];

                    if (!$categoryNo) $missingFields[] = "Category No.";
                    if (!$categoryName) $missingFields[] = "Category";
                    if (!$accessionNo) $missingFields[] = "Accession No.";
                    if (!$title) $missingFields[] = "Title";
                    if (!$author) $missingFields[] = "Author";

                    $errors[] = "Row $key: Missing required data (" . implode(", ", $missingFields) . ").";
                    continue;
                }

                 // Validate if publish_date is numeric
                if (!is_numeric($publishDate)) {
                    $errors[] = "Row $key: Publish Date '$publishDate' is not a valid year.";
                    continue;
                }

                // Get category ID by category name
                $categoryId = getCategoryIDByName($categoryName, $conn);
                if (!$categoryId) {
                    $errors[] = "Row $key: Category '$categoryName' not found in the database.";
                    continue;
                }

                // Check if the book with the same accession number already exists
                $checkBook = $conn->prepare("SELECT 1 FROM books WHERE accession = ?");
                $checkBook->bind_param('s', $accessionNo);
                $checkBook->execute();
                $bookExists = $checkBook->get_result()->num_rows > 0;

                if ($bookExists) {
                    $errors[] = "Row $key: Book with Accession No. '$accessionNo' already exists.";
                    continue;
                }

                // Insert valid row into the database
                $stmt = $conn->prepare(
                    "INSERT INTO books (category_no, category_id, accession, volume, title, author, publisher, publish_date, status) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)"
                );
                $stmt->bind_param('ssssssss', $categoryNo, $categoryId, $accessionNo, $volume, $title, $author, $publisher, $publishDate);

                if ($stmt->execute()) {
                    $successCount++;
                } else {
                    $errors[] = "Row $key: Failed to insert book with Accession No. '$accessionNo'.";
                }
            }

            // Set success and error messages
            if ($successCount > 0) {
                $_SESSION['success'] = "$successCount book(s) imported successfully!";
            }
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
            }

            header('Location: book.php');
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header('Location: book.php');
        }
    } else {
        $_SESSION['error'] = "No file selected.";
        header('Location: book.php');
    }
} else {
    $_SESSION['error'] = 'Fill up the add form first.';
    header('Location: book.php');
}
exit();
