<?php
function deleteDirectory($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_FILES['files']['name'][0])) {
            $username = $_POST['username'];
            $uploadsDir = '../view/uploads/avatar/'. $username . '/';
            if (is_dir($uploadsDir)) {
                deleteDirectory($uploadsDir);
            }
            mkdir($uploadsDir, 0777, true);
            foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                $fileName = basename($_FILES['files']['name'][$key]);
                $targetFilePath = $uploadsDir . $fileName;
                if (move_uploaded_file($tmpName, $targetFilePath)) {
                    $response = array('status' => 'success', 'message' => "File $fileName has been successfully uploaded.", 'path' => $targetFilePath);
                } else {
                    $response = array('status' => 'error', 'message' => "There was an error uploading the file $fileName.");
                }
                echo json_encode($response);
            }
        } else {
            echo "No files have been selected.";
        }
    } else {
        echo "Request method not allowed.";
    }
?>
