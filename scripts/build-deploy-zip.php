#!/usr/bin/env php
<?php

/**
 * Build a deployable ZIP for cPanel/shared hosting.
 * Run from project root: php scripts/build-deploy-zip.php
 */

$root = dirname(__DIR__);
$zipName = 'online-pr-deploy.zip';
$zipPath = $root . DIRECTORY_SEPARATOR . $zipName;

$exclude = [
    '.git',
    '.gitignore',
    '.gitattributes',
    'node_modules',
    '.env',
    '.env.*',
    '*.log',
    '.DS_Store',
    'Thumbs.db',
    'tests',
    'phpunit.xml',
    '.phpunit.result.cache',
    '.phpunit.cache',
    '.idea',
    '.vscode',
    'storage/logs/*',
    'storage/framework/cache/data/*',
    'storage/framework/sessions/*',
    'storage/framework/views/*',
];

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    fwrite(STDERR, "Failed to create $zipName\n");
    exit(1);
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$count = 0;
foreach ($iterator as $item) {
    $path = $item->getPathname();
    $relative = substr($path, strlen($root) + 1);

    $skip = false;
    foreach ($exclude as $pattern) {
        if (fnmatch($pattern, $relative) || fnmatch('*/' . $pattern, $relative) || str_starts_with($relative, $pattern . '/')) {
            $skip = true;
            break;
        }
    }
    if ($skip) {
        continue;
    }

    if ($item->isDir()) {
        $zip->addEmptyDir($relative);
    } else {
        $zip->addFile($path, $relative);
        $count++;
    }
}

$zip->close();

echo "Created $zipName with $count files\n";
echo "Upload this file to your hosting and extract it.\n";
