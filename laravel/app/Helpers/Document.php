<?php

namespace App\Helpers;

class Document
{
    private $fileName;
    private $filePath;
    private $fileType;

    public function __construct($fileName, $fileType)
    {
        $this->fileName = $fileName;
        $this->filePath = storage_path('app/public/' . $fileName);
        $this->fileType = $fileType;
    }

    private function pdfToText()
    {
        return false;
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($this->filePath);
            $text = $pdf->getText();
        }
        catch (\Exception $e) {
            return false;
        }

        return $text;
    }

    private function docxToText()
    {
        if ($this->filePath && file_exists($this->filePath)) {
            $stripedContent = '';
            $content = '';

            $zip = zip_open($this->filePath);
            if ($zip && !is_numeric($zip)) {
                while ($zipEntry = zip_read($zip)) {
                    if (zip_entry_open($zip, $zipEntry) == false) {
                        continue;
                    }

                    if (zip_entry_name($zipEntry) != 'word/document.xml') {
                        continue;
                    }

                    $content .= zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
                    zip_entry_close($zipEntry);
                } // [END] while

                zip_close($zip);

                $content = str_replace('</w:r></w:p></w:tc><w:tc>', ' ', $content);
                $content = str_replace('</w:r></w:p>', "\r\n", $content);
                $stripedContent = strip_tags($content);

                return $stripedContent;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    private function docToText()
    {
        if (file_exists($this->filePath)) {
            if (($fh = fopen($this->filePath, 'r')) !== false) {
                $headers = fread($fh, 0xA00);

                $n1 = (ord($headers[0x21C]) - 1);
                $n2 = ((ord($headers[0x21D]) - 8) * 256);
                $n3 = ((ord($headers[0x21E]) * 256) * 256);
                $n4 = (((ord($headers[0x21F]) * 256) * 256) * 256);

                $textLength = $n1 + $n2 + $n3 + $n4;

                if ($textLength > 0) {
                    $extractedPlaintext = fread($fh, $textLength);
                }
                else {
                    return false;
                }

                return $extractedPlaintext;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    private function pptxToText()
    {
        $zipHandle = new \ZipArchive();
        $response = '';

        if ($zipHandle->open($this->filePath) === true) {
            $slideNumber = 1;
            $doc = new \DOMDocument();

            while (($xmlIndex = $zipHandle->locateName('ppt/slides/slide' . $slideNumber . '.xml')) !== false) {
                $xmlData = $zipHandle->getFromIndex($xmlIndex);

                $doc->loadXML($xmlData, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response .= strip_tags($doc->saveXML());

                $slideNumber++;
            } // [END] while

            $zipHandle->close();

            return $response;
        }
        else {
            return false;
        }
    }

    private function xlsxToText()
    {
        $xmlFileName = 'xl/sharedStrings.xml';
        $zipHandle = new \ZipArchive();
        $response = '';

        if ($zipHandle->open($this->filePath) === true) {
            
            if (($xmlIndex = $zipHandle->locateName($xmlFileName)) !== false) {
                $doc = new \DOMDocument();

                $xmlData = $zipHandle->getFromIndex($xmlIndex);
                $doc->loadXML($xmlData, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $response = strip_tags($doc->saveXML());
            }

            $zipHandle->close();

            return $response;

        }
        else {
            return false;
        }
    }

    public function getText()
    {
        if ($this->fileType == 'pdf') {
            return $this->pdfToText();
        }
        elseif ($this->fileType == 'docx') {
            return $this->docxToText();
        }
        elseif ($this->fileType == 'doc') {
            return $this->docToText();
        }
        elseif ($this->fileType == 'pptx') {
            return $this->pptxToText();
        }
        elseif ($this->fileType == 'xlsx') {
            return $this->xlsxToText();
        }
        else {
            return false;
        }
    }
}