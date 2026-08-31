<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Convert Excel (XLSX/XLS/CSV) to a clean UTF-8 CSV with fixed columns.
 */
if (!function_exists('convert_excel_to_csv')) {
    function convert_excel_to_csv($file_path) {
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $csv_path = str_replace('.' . $ext, '.csv', $file_path);

        if ($ext === 'csv') {
            return clean_csv_file($file_path);
        } elseif ($ext === 'xlsx') {
            return excel_xlsx_to_csv($file_path, $csv_path) ? $csv_path : false;
        } elseif ($ext === 'xls') {
            return excel_xls_to_csv($file_path, $csv_path);
        }
        return false;
    }
}

/**
 * XLSX Reader preserving empty cells.
 */
if (!function_exists('excel_xlsx_to_csv')) {
    function excel_xlsx_to_csv($xlsx_path, $csv_path) {
        $zip = new ZipArchive();
        if ($zip->open($xlsx_path) !== TRUE) return false;

        // Load shared strings
        $sharedStrings = [];
        if (($xml = $zip->getFromName('xl/sharedStrings.xml')) !== FALSE) {
            preg_match_all('/<t[^>]*>(.*?)<\/t>/s', $xml, $m);
            $sharedStrings = $m[1];
        }

        // Load first sheet (usually sheet1.xml)
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === FALSE) return false;

        // Extract rows
        preg_match_all('/<row[^>]*>(.*?)<\/row>/s', $sheetXml, $rows);
        $csv = '';
        foreach ($rows[1] as $rowXml) {
            // Build array for up to 6 columns
            $cells = array_fill(0, 6, '');
            preg_match_all('/<c[^>]*r="([A-Z]+)(\d+)"[^>]*>(.*?)<\/c>/s', $rowXml, $cols, PREG_SET_ORDER);

            foreach ($cols as $cell) {
                list($all, $colLetter, $rowNum, $cellContent) = $cell;
                $index = ord($colLetter) - 65; // A=0
                if ($index < 0 || $index >= 6) continue;

                // Get value
                if (strpos($cellContent, 't="s"') !== FALSE || strpos($cellContent, '<v>') !== FALSE) {
                    if (preg_match('/<v>(.*?)<\/v>/', $cellContent, $v)) {
                        $val = $v[1];
                        // shared string
                        if (preg_match('/t="s"/', $cell[0])) {
                            $val = isset($sharedStrings[intval($val)]) ? $sharedStrings[intval($val)] : $val;
                        }
                    } else {
                        $val = '';
                    }
                } elseif (preg_match('/<t[^>]*>(.*?)<\/t>/', $cellContent, $v)) {
                    $val = $v[1];
                } else {
                    $val = '';
                }

                // Normalize whitespace and remove zero-width chars
                $val = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $val);
                $val = trim(preg_replace("/\s+/", ' ', $val));
                $cells[$index] = $val;
            }

            // Join row, quote each field
            $quoted = array_map(function ($v) {
                $v = str_replace('"', '""', $v);
                return '"' . $v . '"';
            }, $cells);
            $csv .= implode(',', $quoted) . "\n";
        }
        $zip->close();

        file_put_contents($csv_path, "\xEF\xBB\xBF" . $csv);
        return true;
    }
}

/**
 * Handle XLS via LibreOffice or basic fallback.
 */
if (!function_exists('excel_xls_to_csv')) {
    function excel_xls_to_csv($xls_path, $csv_path) {
        if (function_exists('shell_exec')) {
            $cmd = "libreoffice --headless --convert-to csv --outdir " . dirname($xls_path) . " " . escapeshellarg($xls_path);
            @shell_exec($cmd);
            $converted = str_replace('.xls', '.csv', $xls_path);
            if (file_exists($converted)) return clean_csv_file($converted);
        }
        return simple_xls_to_csv($xls_path, $csv_path);
    }
}

/**
 * Basic binary XLS fallback (rarely used).
 */
if (!function_exists('simple_xls_to_csv')) {
    function simple_xls_to_csv($xls_path, $csv_path) {
        $bin = @file_get_contents($xls_path);
        if ($bin === false) return false;
        preg_match_all('/[^\x00-\x1F\x7F-\xFF]{3,}/', $bin, $matches);
        $rows = array_chunk($matches[0], 6);
        $csv = '';
        foreach ($rows as $r) {
            $csv .= '"' . implode('","', array_map(function($v){
                return str_replace('"', '""', trim($v));
            }, $r)) . '"' . "\n";
        }
        file_put_contents($csv_path, "\xEF\xBB\xBF" . $csv);
        return $csv_path;
    }
}

/**
 * Clean CSV of zero-width & non-printable chars.
 */
if (!function_exists('clean_csv_file')) {
    function clean_csv_file($path) {
        $content = @file_get_contents($path);
        $content = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $content);
        file_put_contents($path, $content);
        return $path;
    }
}
?>
