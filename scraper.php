<?php
// Database connection
$db = new mysqli('localhost', 'username', 'password', 'database_name');

// Read IPs from file
$ips = file('valid_ips.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Function to scrape website content
function scrapeWebsite($url, $keyword) {
    $html = file_get_contents($url);
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $paragraphs = $xpath->query("//p[contains(text(), '$keyword')]");
    $result = [];
    $count = 0;
    
    foreach ($paragraphs as $paragraph) {
        if ($count >= 5) break;
        $result[] = $paragraph->textContent;
        $count++;
    }
    
    return $result;
}

// Get keywords from database
$query = "SELECT word FROM word";
$result = $db->query($query);
$keywords = [];
while ($row = $result->fetch_assoc()) {
    $keywords[] = $row['word'];
}

// Scrape websites for each IP and keyword
$scrapedData = [];
foreach ($ips as $ip) {
    foreach ($keywords as $keyword) {
        $url = "http://$ip";
        $scrapedData[$ip][$keyword] = scrapeWebsite($url, $keyword);
    }
}

// Output JSON for frontend
header('Content-Type: application/json');
echo json_encode($scrapedData);
?>