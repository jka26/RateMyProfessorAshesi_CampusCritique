<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to submit a review.";
    header("Location: ../home.php");
    exit();
}
$user_id = $_SESSION['user_id'];

require_once('../config/db.php');

// Defining moderation function
function moderate_comment($comment) {
    $data = json_encode(['text' => $comment]);
    $ch = curl_init('http://localhost:5001/moderate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $comment = trim($_POST['comment']);

    // 🛡️ Moderate content
    $moderation = moderate_comment($comment);
    if (isset($moderation['flagged']) && $moderation['flagged'] === true) {
        $_SESSION['error'] = "⚠️ Your comment is sensitive and cannot be posted.";
        header("Location: " . $target_type . "_details.php?id=" . $target_id);
        exit();
    }

    
// 🔍 Defining sentiment analysis function
function analyze_sentiment($comment) {
    $data = json_encode(['text' => $comment]);
    $ch = curl_init('http://localhost:5000/sentiment');  // Flask server must be running
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $target_type = $_POST['target_type'];
    $target_id = $_POST['target_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    // 🧠 Analyze sentiment before storing
    $analysis = analyze_sentiment($comment);
    $sentiment = $analysis['sentiment']; // e.g., 'positive', 'neutral', 'negative'

    // 🚫 Optional: Block negative comments
    // if ($sentiment === 'negative') {
    //     $_SESSION['error'] = "Your comment is too negative and cannot be submitted.";
    //     header("Location: " . $target_type . "_details.php?id=" . $target_id);
    //     exit();
    // }

    // Check if the user has already reviewed this item
    $checkQuery = $conn->prepare("SELECT * FROM reviews WHERE user_id = ? AND target_type = ? AND target_id = ?");
    $checkQuery->bind_param("iss", $user_id, $target_type, $target_id);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "You have already submitted a review for this " . $target_type . ".";
        header("Location: " . $target_type . "_details.php?id=" . $target_id);
        exit();
    }

    // ✅ Insert the review, including sentiment
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, target_type, target_id, rating, comment, sentiment, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ississ", $user_id, $target_type, $target_id, $rating, $comment, $sentiment);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Review submitted successfully.";
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }

    header("Location: " . $target_type . "_details.php?id=" . $target_id);
    $stmt->close();
    $conn->close();
}
?>
