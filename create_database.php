<?php
$servername = "localhost";
$username = "root";
$password = "";
$database_name="fb_athenslair";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql="DROP DATABASE ".$database_name;
$conn->query($sql);

// Create database
$sql = "CREATE DATABASE ".$database_name;
if ($conn->query($sql) === TRUE) {
    //echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

//select database
mysqli_select_db($conn,$database_name);

// sql to create table POSTS
$sql = "CREATE TABLE POSTS (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pid VARCHAR(100) NOT NULL,
author VARCHAR(100),
time VARCHAR(100),
message LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
picture VARCHAR(100),
link VARCHAR(100),
name VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
description VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
inforum INT DEFAULT 0,
title VARCHAR(100)
)";

if ($conn->query($sql) === TRUE) {
    //echo "Table POSTS created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// sql to create table COMMENTS
$sql = "CREATE TABLE COMMENTS (
id VARCHAR(100) PRIMARY KEY,
pid VARCHAR(100) NOT NULL,
author VARCHAR(100),
time VARCHAR(100),
message LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
picture VARCHAR(100),
link VARCHAR(100),
name VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
description VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci
)";

if ($conn->query($sql) === TRUE) {
    //echo "Table COMMENTS created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}



$sql = "CREATE TABLE NICKNAMES (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
realname VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci UNIQUE,
nickname VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 0
)";

if ($conn->query($sql) === TRUE) {
    //echo "Table COMMENTS created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


//close mysql connection
$conn->close();


?> 
