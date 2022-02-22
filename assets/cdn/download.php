#!/usr/bin/env php
<?php
if (PHP_SAPI != 'cli') {
    exit;
}

const CDN_URL = 'https://cdn.staticfile.org/';
const RE_SOURCE = '@/\*# sourceMappingURL=(.+?) \*/|//# sourceMappingURL=(.+?)$@';

function download($name, $isMap = false) {
    $url = CDN_URL . $name;
    $path = join('/', [__DIR__, 'local', $name]);
    $dir = dirname($path);
    if (!file_exists($dir)) {
        mkdir($dir, null, true);
    }
    printf("%s => %s\n", $url, $path);
    $text = file_get_contents($url);
    if (file_put_contents($path, $text) === false) {
        echo "Download failed\n";
    }
    if (!$isMap && preg_match(RE_SOURCE, $text, $math)) {
        $source = $math[1];
        if (!$source) {
            $source = $math[2];
        }
        $name = dirname($name) . '/' . $source;
        download($name, true);
    }
}

function main() {
    $fp = fopen(__DIR__ . '/urls.txt', 'r');
    while (($line = fgets($fp, null)) !== false) {
        download(trim($line));
    }
    fclose($fp);
}

main();
