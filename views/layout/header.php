<?php
session_start();
require_once __DIR__ . '/../../config.php';
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Events</title>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-success">
        <div class="container">
            <a class="navbar-brand" href="/">Ollyo Event</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left-aligned items -->
                <ul class="navbar-nav mr-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="/views/event/index.php">Add Event</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="/views/register.php">Register</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="/views/login.php">Login</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="/views/logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Right-aligned items -->

    </nav>
    </div>
    <div class="container">
        <div class="card">