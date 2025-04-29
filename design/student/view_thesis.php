<?php
// Database connection
include 'connection.php'; // Ensure this file contains the database connection setup

// Get thesis ID from the URL
if (isset($_GET['id'])) {
    $thesis_id = $_GET['id'];

    // Fetch abstract image path from the database
    $query = "SELECT abstract_image FROM thesis WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $thesis_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the data
        $row = $result->fetch_assoc();
        
        // Display abstract image or file
        if (!empty($row['abstract_image'])) {
            // Use htmlspecialchars to prevent XSS
            $abstractImage = htmlspecialchars($row['abstract_image']);
            // Ensure to add the path to the assets/images folder
            $abstractImagePath = "assets/images/" . $abstractImage; // Assuming the stored file name doesn't include the path
            
            // Check if the file exists before displaying
            if (file_exists($abstractImagePath)) {
                // Get the file extension
                $file_extension = strtolower(pathinfo($abstractImagePath, PATHINFO_EXTENSION));

                // Define valid image types and generic file types
                $valid_image_types = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                $valid_file_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx']; // Example for document types
                
                if (in_array($file_extension, $valid_image_types)) {
                    // If it's an image, display it with fixed size
                    echo "<img src=\"$abstractImagePath\" alt=\"Abstract Image\" style=\"width: 500px; height: 600px; object-fit: cover;\">"; // Set desired width and height
                } elseif (in_array($file_extension, $valid_file_types)) {
                    // If it's a valid file type, provide a link to download it
                    echo "<p><a href=\"$abstractImagePath\" download>Download File</a></p>";
                } else {
                    echo "<p>Invalid file format. Supported formats: jpg, jpeg, png, gif, bmp, webp, pdf, doc, docx, ppt, pptx.</p>";
                }
            } else {
                echo "<p>File does not exist.</p>";
            }
        } else {
            echo "<p>No abstract image or file available.</p>";
        }
    } else {
        echo "<p>Thesis not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>No thesis ID specified.</p>";
}

// Close the database connection
$conn->close();
?>
