<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('excel_to_csv')) {
    function excel_to_csv($excel_path, $csv_path) {
        // Simple XLSX to CSV converter (for basic files)
        $zip = new ZipArchive;
        
        if ($zip->open($excel_path) === TRUE) {
            // Get shared strings
            $sharedStrings = array();
            if (($sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml')) !== FALSE) {
                preg_match_all('/<t>(.*?)<\/t>/s', $sharedStringsXml, $matches);
                $sharedStrings = $matches[1];
            }
            
            // Get sheet data
            $sheetData = array();
            if (($workbookXml = $zip->getFromName('xl/workbook.xml')) !== FALSE) {
                preg_match('/<sheet name="([^"]+)" sheetId="1"/', $workbookXml, $sheetMatch);
                $sheetName = isset($sheetMatch[1]) ? $sheetMatch[1] : 'Sheet1';
            }
            
            if (($sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml')) !== FALSE) {
                preg_match_all('/<row.*?>(.*?)<\/row>/s', $sheetXml, $rows);
                
                $csv_content = '';
                $row_count = 0;
                
                foreach ($rows[1] as $row) {
                    preg_match_all('/<c[^>]*>(.*?)<\/c>/s', $row, $cells);
                    $row_data = array();
                    
                    foreach ($cells[1] as $cell_index => $cell) {
                        if (preg_match('/<v>(.*?)<\/v>/s', $cell, $value)) {
                            $cell_value = $value[1];
                            
                            // Check if it's a shared string
                            if (strpos($cells[0][$cell_index], 't="s"') !== FALSE) {
                                $cell_value = isset($sharedStrings[intval($cell_value)]) ? 
                                    $sharedStrings[intval($cell_value)] : $cell_value;
                            }
                            
                            $row_data[] = '"' . str_replace('"', '""', $cell_value) . '"';
                        } else {
                            $row_data[] = '""';
                        }
                    }
                    
                    $csv_content .= implode(',', $row_data) . "\n";
                    $row_count++;
                    
                    // Safety limit to prevent memory issues
                    if ($row_count > 1000) {
                        break;
                    }
                }
                
                file_put_contents($csv_path, $csv_content);
            }
            
            $zip->close();
            return true;
        }
        
        return false;
    }
}

if (!function_exists('convert_excel_to_csv')) {
    function convert_excel_to_csv($file_path) {
        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $csv_path = str_replace('.' . $extension, '.csv', $file_path);
        
        if ($extension == 'csv') {
            return $file_path; // Already CSV
        }
        
        if ($extension == 'xlsx') {
            return excel_to_csv($file_path, $csv_path) ? $csv_path : false;
        }
        
        // For XLS files, we'll use a simpler approach or shell command
        if ($extension == 'xls') {
            // Try using shell command if available
            if (function_exists('shell_exec')) {
                $command = "libreoffice --headless --convert-to csv --outdir " . dirname($file_path) . " " . escapeshellarg($file_path);
                @shell_exec($command);
                
                $converted_file = str_replace('.xls', '.csv', $file_path);
                if (file_exists($converted_file)) {
                    return $converted_file;
                }
            }
            
            // Fallback: try simple text extraction
            return simple_xls_to_csv($file_path, $csv_path);
        }
        
        return false;
    }
}

if (!function_exists('simple_xls_to_csv')) {
    function simple_xls_to_csv($xls_path, $csv_path) {
        // Very basic XLS reader using file reading
        $handle = fopen($xls_path, 'rb');
        $content = fread($handle, filesize($xls_path));
        fclose($handle);
        
        // Extract text content (very basic)
        preg_match_all('/[^\x00-\x1F\x7F-\xFF]{3,}/', $content, $matches);
        
        $rows = array_chunk($matches[0], 6); // Assuming 6 columns
        $csv_content = '';
        
        foreach ($rows as $row) {
            $csv_content .= '"' . implode('","', array_map(function($value) {
                return str_replace('"', '""', $value);
            }, $row)) . '"' . "\n";
        }
        
        file_put_contents($csv_path, $csv_content);
        return $csv_path;
    }
}