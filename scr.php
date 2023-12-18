<?php

$filename = "wines.txt";
$screenshotsDir = "screenshots";

if (!file_exists($screenshotsDir)) {
    mkdir($screenshotsDir, 0777, true);
}

$domains = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($domains as $domain) {
    // URL validity check
    if (!filter_var($domain, FILTER_VALIDATE_URL)) {
        $domain = "https://" . $domain;
    }

    if (!filter_var($domain, FILTER_VALIDATE_URL)) {
        echo "Invalid URL: $domain\n";
        continue;
    }

    // Retrieve a clean domain name to use as a filename
    $domainForFilename = parse_url($domain, PHP_URL_HOST);
    // Remove possible invalid characters in the filename
    $domainForFilename = preg_replace('/[^a-zA-Z0-9\-]/', '_', $domainForFilename);

    $filePath = $screenshotsDir . DIRECTORY_SEPARATOR . $domainForFilename . '.jpg';

    // Check if a file exists before creating a screenshot
    if (file_exists($filePath)) {
        echo "Screenshot for $domain already exists. Skip it.\n";
        continue;
    }

    $command = "node screenshot.js " . escapeshellarg($domain) . " " . escapeshellarg($filePath);
    $output = shell_exec($command);

    // Print errors, if any
    if (!empty($output)) {
        echo "Error with $domain: $output\n.";
    }

    // Wait 1 second before the next request
    sleep(1);
}

echo "Screenshots have been saved.\n"
?>
