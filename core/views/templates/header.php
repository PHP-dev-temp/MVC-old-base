<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>USER forms</title>
</head>
<body>
<?php if(isset($_SESSION['flash_message'])): ?>
<div class="flash-message"><?= $_SESSION['flash_message'] ?></div>
<?php unset($_SESSION['flash_message']); endif; ?>
