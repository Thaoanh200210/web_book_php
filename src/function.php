<?php
    namespace Project\src;

    require "../vendor/autoload.php";

    function redirect(string $path) { 
        header("Location: ".$path);
        exit();
    }

    function checkUploadFile($targetFile, $tempFile) {
        $upload = 0;
        $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

        if(getimagesize($tempFile)) {
            if($imageFileType == "jpg"){
                $upload = 1;
            }
        }
        
        return $upload; 
    }