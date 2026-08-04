<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

class DocumentConverter
{
    /**
     * Convert Word or Excel document to PDF binary string.
     */
    public static function convertToPdf(string $sourcePath, string $extension): ?string
    {
        $extension = strtolower($extension);

        if ($extension === 'pdf') {
            return file_get_contents($sourcePath);
        }

        try {
            if (in_array($extension, ['doc', 'docx'])) {
                return self::convertWordToPdf($sourcePath);
            }

            if (in_array($extension, ['xls', 'xlsx'])) {
                return self::convertExcelToPdf($sourcePath);
            }
        } catch (\Throwable $e) {
            return self::createFallbackPdf($e->getMessage(), basename($sourcePath));
        }

        return null;
    }

    private static function convertWordToPdf(string $sourcePath): string
    {
        $tempPdf = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'doc_conv_' . uniqid() . '.pdf';
        $psScript = storage_path('app/convert_doc_to_pdf.ps1');

        if (file_exists($psScript) && PHP_OS_FAMILY === 'Windows') {
            $cmd = sprintf('powershell -ExecutionPolicy Bypass -File %s %s %s', 
                escapeshellarg($psScript), 
                escapeshellarg($sourcePath), 
                escapeshellarg($tempPdf)
            );
            @exec($cmd);

            if (file_exists($tempPdf) && filesize($tempPdf) > 0) {
                $content = file_get_contents($tempPdf);
                @unlink($tempPdf);
                return $content;
            }
        }

        // Fallback to PhpWord + Dompdf
        $phpWord = WordIOFactory::load($sourcePath);
        $htmlWriter = WordIOFactory::createWriter($phpWord, 'HTML');
        ob_start();
        $htmlWriter->save('php://output');
        $rawHtml = ob_get_clean();

        $customStyles = "
            <style>
                @page { margin: 20mm 15mm; }
                body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; line-height: 1.6; color: #1e293b; background: #ffffff; }
                p { margin-bottom: 10pt; text-align: justify; }
                h1, h2, h3, h4, h5, h6 { font-family: 'Helvetica', 'Arial', sans-serif; color: #0f172a; margin-top: 14pt; margin-bottom: 8pt; font-weight: bold; }
                h1 { font-size: 18pt; border-bottom: 2px solid #059669; padding-bottom: 4pt; color: #059669; }
                h2 { font-size: 15pt; color: #047857; }
                h3 { font-size: 12.5pt; }
                table { width: 100% !important; border-collapse: collapse !important; margin: 12pt 0; page-break-inside: avoid; }
                th, td { border: 1px solid #cbd5e1 !important; padding: 6pt 10pt !important; font-size: 10pt !important; vertical-align: top; }
                th { background-color: #f1f5f9 !important; font-weight: bold; color: #0f172a; }
                img { max-width: 100%; height: auto; display: block; margin: 10pt auto; }
                ul, ol { margin-left: 20pt; margin-bottom: 10pt; }
                li { margin-bottom: 4pt; }
            </style>
        ";

        if (str_contains($rawHtml, '</head>')) {
            $htmlContent = str_replace('</head>', $customStyles . '</head>', $rawHtml);
        } else {
            $htmlContent = '<html><head><meta charset="utf-8">' . $customStyles . '</head><body>' . $rawHtml . '</body></html>';
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private static function convertExcelToPdf(string $sourcePath): string
    {
        $tempPdf = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'xls_conv_' . uniqid() . '.pdf';
        $psScript = storage_path('app/convert_xls_to_pdf.ps1');

        if (file_exists($psScript) && PHP_OS_FAMILY === 'Windows') {
            $cmd = sprintf('powershell -ExecutionPolicy Bypass -File %s %s %s', 
                escapeshellarg($psScript), 
                escapeshellarg($sourcePath), 
                escapeshellarg($tempPdf)
            );
            @exec($cmd);

            if (file_exists($tempPdf) && filesize($tempPdf) > 0) {
                $content = file_get_contents($tempPdf);
                @unlink($tempPdf);
                return $content;
            }
        }

        // Fallback to PhpSpreadsheet + Dompdf
        $spreadsheet = SpreadsheetIOFactory::load($sourcePath);
        $htmlWriter = new \PhpOffice\PhpSpreadsheet\Writer\Html($spreadsheet);
        $htmlWriter->setGenerateSheetNavigationBlock(false);

        ob_start();
        $htmlWriter->save('php://output');
        $rawHtml = ob_get_clean();

        $customStyles = "
            <style>
                @page { margin: 15mm 10mm; }
                body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #1e293b; background: #ffffff; }
                table { width: 100% !important; border-collapse: collapse !important; margin-bottom: 20px; page-break-inside: auto; }
                tr { page-break-inside: avoid; }
                td, th { border: 1px solid #94a3b8 !important; padding: 5px 8px !important; font-size: 8.5pt !important; vertical-align: middle; }
                th { background-color: #e2e8f0 !important; font-weight: bold; color: #0f172a; }
            </style>
        ";

        if (str_contains($rawHtml, '</head>')) {
            $htmlContent = str_replace('</head>', $customStyles . '</head>', $rawHtml);
        } else {
            $htmlContent = '<html><head><meta charset="utf-8">' . $customStyles . '</head><body>' . $rawHtml . '</body></html>';
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    private static function createFallbackPdf(string $errorMsg, string $filename): string
    {
        $options = new Options();
        $dompdf = new Dompdf($options);
        $html = "
            <html>
            <body style='font-family: Arial, sans-serif; padding: 40px; color: #1e293b; background: #f8fafc;'>
                <div style='background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 30px; text-align: center;'>
                    <h2 style='color: #059669; margin-bottom: 10px;'>CSA-ITGC Read-Only Document Preview</h2>
                    <h4 style='color: #475569; font-weight: normal; margin-bottom: 20px;'>" . htmlspecialchars($filename) . "</h4>
                    <div style='background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; padding: 15px; border-radius: 6px; font-size: 14px;'>
                        Document loaded in Read-Only Mode. Click Download below to save the original file.
                    </div>
                </div>
            </body>
            </html>
        ";
        $dompdf->loadHtml($html);
        $dompdf->render();
        return $dompdf->output();
    }
}
