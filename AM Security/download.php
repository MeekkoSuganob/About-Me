<?php
session_start();
include 'config.php';

// Must be logged in to download
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$os = $_GET['os'] ?? 'windows';
$os = in_array($os, ['windows', 'macos']) ? $os : 'windows';

// no subscription - no download
$stmt = $conn->prepare("SELECT plan_name, license_key, expires_at FROM subscriptions WHERE user_id = ? AND expires_at > NOW() ORDER BY expires_at DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$subscription = $result->fetch_assoc();
$stmt->close();
$conn->close();

// no subscription - go buy
if (!$subscription) {
    header("Location: services.php");
    exit();
}

// ── Build the placeholder file content ──────────────────────────────────
$osLabel = $os === 'macos' ? 'macOS' : 'Windows';

$content = "AM Security - {$osLabel} Edition\r\n";
$content .= " \r\n\r\n";
$content .= "Plan: {$subscription['plan_name']}\r\n";
$content .= "License Key: {$subscription['license_key']}\r\n";
$content .= "Subscription Expires: {$subscription['expires_at']}\r\n\r\n";
$content .= "Enter your license key when prompted during setup to activate.\r\n\r\n";
$content .= "Thank you for choosing AM Security.\r\n";

// ── Serve it as a downloadable file ──────────────────────────────────────
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="AMSecurity-' . $osLabel . '-Setup.txt"');
header('Content-Length: ' . strlen($content));
echo $content;
exit();