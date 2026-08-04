$word = New-Object -ComObject Word.Application;
$doc = $word.Documents.Add();
$selection = $word.Selection;
$selection.TypeText("This is a test document for ITGC evidence.");
$doc.SaveAs([ref] 'd:\csa-itgc\test_evidence.docx');
$doc.Close();
$word.Quit();
