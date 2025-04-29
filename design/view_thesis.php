<?php
// Database connection
include 'connection.php';

if (isset($_GET['id'])) {
    $thesis_id = (int)$_GET['id']; // Cast to integer for security

    $query = "SELECT abstract_image FROM thesis WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $thesis_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $abstractImage = trim($row['abstract_image']);

        if (!empty($abstractImage)) {
            // Ensure no duplicate 'uploads/' in path
            $abstractImage = ltrim($abstractImage, '/'); // Remove leading slash if any
            $basePath = "uploads/";
            $abstractImagePath = $basePath . basename($abstractImage); // basename for extra safety

            if (file_exists($abstractImagePath)) {
                $file_extension = strtolower(pathinfo($abstractImagePath, PATHINFO_EXTENSION));

                $valid_image_types = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                $valid_file_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

                echo '<div style="text-align:center; padding: 20px;">';

                if (in_array($file_extension, $valid_image_types)) {
                    echo "<img src=\"" . htmlspecialchars($abstractImagePath) . "\" alt=\"Abstract Image\" style=\"max-width:100%; height:auto; border:1px solid #ccc;\">";
                } elseif (in_array($file_extension, $valid_file_types)) {
                    echo "<p><strong>Download:</strong> <a href=\"" . htmlspecialchars($abstractImagePath) . "\" download>Click here to download the abstract file</a></p>";
                } else {
                    echo "<p>Unsupported file format. Supported formats: jpg, jpeg, png, gif, bmp, webp, pdf, doc, docx, ppt, pptx.</p>";
                }

                echo '</div>';
            } else {
                echo "<p style='color:red; text-align:center;'>❌ File does not exist at: <code>$abstractImagePath</code></p>";
            }
        } else {
            echo "<p style='text-align:center;'>⚠️ No abstract image or file uploaded for this thesis.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Thesis not found.</p>";
    }

    $stmt->close();
} else {
    echo "<p style='color:red; text-align:center;'>No thesis ID specified in the request.</p>";
}

$conn->close();
?>
