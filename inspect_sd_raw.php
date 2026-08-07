<?php

$extractedPath = __DIR__ . '/storage/app/sd_raw_extracted';

// 1. Shared Strings
$sharedStrings = [];
if (file_exists($extractedPath . '/xl/sharedStrings.xml')) {
    $xml = simplexml_load_file($extractedPath . '/xl/sharedStrings.xml');
    foreach ($xml->si as $si) {
        if (isset($si->t)) {
            $sharedStrings[] = (string)$si->t;
        } elseif (isset($si->r)) {
            $t = '';
            foreach ($si->r as $r) {
                $t .= (string)$r->t;
            }
            $sharedStrings[] = $t;
        } else {
            $sharedStrings[] = '';
        }
    }
}

// 2. Sheet names
$sheetNames = [];
if (file_exists($extractedPath . '/xl/workbook.xml')) {
    $wb = simplexml_load_file($extractedPath . '/xl/workbook.xml');
    foreach ($wb->sheets->sheet as $s) {
        $sheetNames[] = (string)$s['name'];
    }
}

echo "=== DAFTAR SHEET DAFTAR NAMA SISWA SD ===" . PHP_EOL;
foreach ($sheetNames as $idx => $sName) {
    echo ($idx + 1) . ". " . $sName . PHP_EOL;
}

// 3. Worksheets
$files = glob($extractedPath . '/xl/worksheets/sheet*.xml');
sort($files);

foreach ($files as $idx => $sheetFile) {
    $sName = $sheetNames[$idx] ?? "Sheet " . ($idx + 1);
    echo PHP_EOL . "==========================================" . PHP_EOL;
    echo "MEMBACA SHEET: {$sName} ({$sheetFile})" . PHP_EOL;
    echo "==========================================" . PHP_EOL;

    $xml = simplexml_load_file($sheetFile);
    $rowCount = 0;
    foreach ($xml->sheetData->row as $row) {
        $rowCount++;
        $rowNum = (int)$row['r'];
        $cols = [];
        foreach ($row->c as $c) {
            $cellRef = (string)$c['r'];
            $cellType = (string)$c['t'];
            $val = (string)$c->v;

            if ($cellType === 's') {
                $val = $sharedStrings[(int)$val] ?? $val;
            }

            $colLetter = preg_replace('/[0-9]/', '', $cellRef);
            $cols[$colLetter] = trim($val);
        }

        if ($rowCount <= 10) {
            $formatted = [];
            foreach ($cols as $colLetter => $val) {
                $formatted[] = "{$colLetter}: {$val}";
            }
            echo "Row {$rowNum} => " . implode(' | ', $formatted) . PHP_EOL;
        }
    }
    echo "Total data baris: {$rowCount}" . PHP_EOL;
}
