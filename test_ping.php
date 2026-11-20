<?php
// Quick test page to verify Apache/PHP serving from DocumentRoot
echo "OK - Apache/PHP serving from DocumentRoot.\n";
echo "Requested URI: " . ($_SERVER['REQUEST_URI'] ?? '') . "\n";
echo "Document root: " . ($_SERVER['DOCUMENT_ROOT'] ?? '') . "\n";
?>
