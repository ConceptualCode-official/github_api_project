<?php  
// One folder back
require_once "../db.php";

$username = "ConceptualCode-official";  

// GitHub API URL
$apiURL = "https://api.github.com/users/$username/repos?per_page=100";

// CURL init
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $apiURL);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch, CURLOPT_USERAGENT, $username); // required

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Validate API response
if ($httpCode !== 200) {
    die(json_encode([
        "status" => false,
        "message" => "GitHub API Error: HTTP $httpCode (Maybe rate limited)",
        "repos" => []
    ], JSON_PRETTY_PRINT));
}

$repos = json_decode($response, true);  

if (!$repos) {  
    die(json_encode([
        "status" => false,
        "message" => "Failed to parse GitHub JSON",
        "repos" => []
    ], JSON_PRETTY_PRINT));
}  

// INSERT / UPDATE SQL
$insertStmt = $pdo->prepare("
    INSERT INTO github_repos (
        id, name, full_name, html_url, description, fork,
        stargazers_count, watchers_count, language,
        forks_count, open_issues_count, default_branch, updated_at
    ) VALUES (
        :id, :name, :full_name, :html_url, :description, :fork,
        :stargazers_count, :watchers_count, :language,
        :forks_count, :open_issues_count, :default_branch, NOW()
    )
    ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        full_name = VALUES(full_name),
        html_url = VALUES(html_url),
        description = VALUES(description),
        fork = VALUES(fork),
        stargazers_count = VALUES(stargazers_count),
        watchers_count = VALUES(watchers_count),
        language = VALUES(language),
        forks_count = VALUES(forks_count),
        open_issues_count = VALUES(open_issues_count),
        default_branch = VALUES(default_branch),
        updated_at = NOW()
");

// Save to DB
foreach ($repos as $repo) {  
    $insertStmt->execute([
        ':id' => $repo['id'],
        ':name' => $repo['name'],
        ':full_name' => $repo['full_name'],
        ':html_url' => $repo['html_url'],
        ':description' => $repo['description'],
        ':fork' => $repo['fork'],
        ':stargazers_count' => $repo['stargazers_count'],
        ':watchers_count' => $repo['watchers_count'],
        ':language' => $repo['language'],
        ':forks_count' => $repo['forks_count'],
        ':open_issues_count' => $repo['open_issues_count'],
        ':default_branch' => $repo['default_branch']
    ]);
}

// Final output EXACT GitHub API style
echo json_encode($repos, JSON_PRETTY_PRINT);