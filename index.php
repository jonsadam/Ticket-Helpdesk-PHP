<?php
if (!file_exists(__DIR__ . '/.installed')) {
    header("Location: install.php");
    exit;
}
    header("Location: public/");
    exit;
?>