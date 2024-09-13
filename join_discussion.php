<?php
// join_discussion.php

// Start session management
session_start();

// Include necessary files for database connection
include('configss.php');  // Database configuration
include('functions.php'); // Functions for handling discussions and users

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check if discussion ID is provided
if (!isset($_GET['discussion_id']) || !is_numeric($_GET['discussion_id'])) {
    echo "Invalid discussion ID.";
    exit();
}

$discussion_id = intval($_GET['discussion_id']);

// Fetch discussion details
$discussion = getDiscussionDetails($discussion_id);

if (!$discussion) {
    echo "Discussion not found.";
    exit();
}

// Check if user is already a member of the discussion
if (isUserInDiscussion($user_id, $discussion_id)) {
    echo "You are already a member of this discussion.";
    exit();
}

// Add user to discussion
if (joinDiscussion($user_id, $discussion_id)) {
    echo "You have successfully joined the discussion!";
    // Optionally, redirect to discussion page
    header("Location: discussion.php?id=$discussion_id");
    exit();
} else {
    echo "Failed to join the discussion. Please try again later.";
}

// Function definitions

/**
 * Fetch discussion details from the database
 */
function getDiscussionDetails($discussion_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM discussions WHERE id = ?");
    $stmt->execute([$discussion_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Check if a user is already in a discussion
 */
function isUserInDiscussion($user_id, $discussion_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM discussion_members WHERE user_id = ? AND discussion_id = ?");
    $stmt->execute([$user_id, $discussion_id]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Add a user to a discussion
 */
function joinDiscussion($user_id, $discussion_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO discussion_members (user_id, discussion_id) VALUES (?, ?)");
    return $stmt->execute([$user_id, $discussion_id]);
}
?>
