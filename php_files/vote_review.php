<?php
session_start();
require_once('../config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to vote on reviews.']);
    exit();
}

// Check if request is POST and has required parameters
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['review_id']) || !isset($_POST['vote_type'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit();
}

$review_id = (int)$_POST['review_id'];
$user_id = $_SESSION['user_id'];
$vote_type = $_POST['vote_type'];

// Validate vote type
if ($vote_type !== 'upvote' && $vote_type !== 'downvote') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid vote type.']);
    exit();
}

// Check if the review exists
$checkReview = $conn->prepare("SELECT review_id FROM reviews WHERE review_id = ?");
$checkReview->bind_param("i", $review_id);
$checkReview->execute();
$reviewResult = $checkReview->get_result();

if ($reviewResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Review not found.']);
    exit();
}

// Check if the user has already voted on this review
$checkVote = $conn->prepare("SELECT id, vote_type FROM review_votes WHERE review_id = ? AND user_id = ?");
$checkVote->bind_param("ii", $review_id, $user_id);
$checkVote->execute();
$voteResult = $checkVote->get_result();

if ($voteResult->num_rows > 0) {
    // User has already voted on this review
    $existingVote = $voteResult->fetch_assoc();
    
    if ($existingVote['vote_type'] === $vote_type) {
        // If voting the same way, remove the vote (toggle)
        $deleteVote = $conn->prepare("DELETE FROM review_votes WHERE id = ?");
        $deleteVote->bind_param("i", $existingVote['id']);
        
        if ($deleteVote->execute()) {
            echo json_encode(['success' => true, 'action' => 'removed', 'vote_type' => $vote_type]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update vote.']);
        }
    } else {
        // If voting differently, update the vote
        $updateVote = $conn->prepare("UPDATE review_votes SET vote_type = ? WHERE id = ?");
        $updateVote->bind_param("si", $vote_type, $existingVote['id']);
        
        if ($updateVote->execute()) {
            echo json_encode(['success' => true, 'action' => 'changed', 'vote_type' => $vote_type]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update vote.']);
        }
    }
} else {
    // User has not voted on this review yet, so insert a new vote
    $insertVote = $conn->prepare("INSERT INTO review_votes (review_id, user_id, vote_type) VALUES (?, ?, ?)");
    $insertVote->bind_param("iis", $review_id, $user_id, $vote_type);
    
    if ($insertVote->execute()) {
        echo json_encode(['success' => true, 'action' => 'added', 'vote_type' => $vote_type]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to add vote.']);
    }
}

$conn->close();
?>