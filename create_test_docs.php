<?php
require 'vendor/autoload.php';
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$section->addText('Test DOCX Document');
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('test.docx');

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Test XLSX Document');
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('test.xlsx');

// create a dummy PDF using TCPDF or DomPDF
$dompdf = new \Dompdf\Dompdf();
$dompdf->loadHtml('<h1>Test PDF Document</h1>');
$dompdf->render();
file_put_contents('test.pdf', $dompdf->output());

echo "Files created.";
