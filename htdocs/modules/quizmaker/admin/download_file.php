<?php
$fullName = $_GET['fullName'];
if(isset($_GET['typeDownload'])){
    $typeDownload = $_GET['typeDownload'];
}else{
    $typeDownload = 'text/csv';
}


    //rewind($f) ;
    // Définir les entêtes pour le téléchargement du fichier CSV
    header("Content-Type: {$typeDownload}");
    header('Content-Disposition: attachment; filename="' . basename($fullName) . '";');

    $fp = fopen('php://memory', 'w');
    readfile($fullName);
    
    // Sortir le contenu du fichier
    fpassthru($fp);
//rewind($fp); 
    fclose($fp);

    exit;
?>