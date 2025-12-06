<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// ----------------------------------------------------
// DB Connection (folder back)
// ----------------------------------------------------
require '../db.php';

// ----------------------------------------------------
// Input Validation
// ----------------------------------------------------
$username = trim($_GET['user'] ?? '');
if (!$username) {
    echo json_encode(['error' => 'No username provided']);
    exit;
}

// ----------------------------------------------------
// Check DB Cache (1 hour)
// ----------------------------------------------------
$stmt = $pdo->prepare("
    SELECT * FROM github_users 
    WHERE login = ? 
    AND last_fetched > NOW() - INTERVAL 1 HOUR
");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user);
    exit;
}

// ----------------------------------------------------
// Fetch from GitHub API
// ----------------------------------------------------
$url = "https://api.github.com/users/$username";

$context = stream_context_create([
    "http" => [
        "header" => "User-Agent: PortfolioApp"
    ]
]);

$data = @file_get_contents($url, false, $context);

if (!$data) {
    echo json_encode(['error' => 'Unable to fetch GitHub API']);
    exit;
}

$userData = json_decode($data, true);

if (isset($userData['message'])) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

// ----------------------------------------------------
// Insert / Update DB
// ----------------------------------------------------
$upsert = $pdo->prepare("
    INSERT INTO github_users
    (login, name, avatar_url, bio, followers, following, public_repos, public_gists, type,
     company, blog, location, email, hireable, twitter_username, created_at, updated_at, last_fetched)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        avatar_url = VALUES(avatar_url),
        bio = VALUES(bio),
        followers = VALUES(followers),
        following = VALUES(following),
        public_repos = VALUES(public_repos),
        public_gists = VALUES(public_gists),
        type = VALUES(type),
        company = VALUES(company),
        blog = VALUES(blog),
        location = VALUES(location),
        email = VALUES(email),
        hireable = VALUES(hireable),
        twitter_username = VALUES(twitter_username),
        created_at = VALUES(created_at),
        updated_at = VALUES(updated_at),
        last_fetched = NOW()
");

$upsert->execute([
    $userData['login'],
    $userData['name'] ?? null,
    $userData['avatar_url'] ?? null,
    $userData['bio'] ?? null,
    $userData['followers'] ?? 0,
    $userData['following'] ?? 0,
    $userData['public_repos'] ?? 0,
    $userData['public_gists'] ?? 0,
    $userData['type'] ?? null,
    $userData['company'] ?? null,
    $userData['blog'] ?? null,
    $userData['location'] ?? null,
    $userData['email'] ?? null,
    $userData['hireable'] ?? null,
    $userData['twitter_username'] ?? null,
    $userData['created_at'] ?? null,
    $userData['updated_at'] ?? null
]);

echo json_encode($userData);