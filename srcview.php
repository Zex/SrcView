<?php
// srcview.php
//
// Author: Zex <top_zlynch@yahoo.com>
//

require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();

    try {

        $filename = "message.cpp";
        $pdf = new Zend_Pdf();
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $font_sz = 15;
        $line_space = 1;
        
        $buf = file($filename);
        $line_nr = $font_sz;
        $page_nr = 0;
        $indent = 10;
        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
        $page->setFont($font, $font_sz);
    
        foreach ($buf as $nr => $line) {
            $page->drawText($line, $indent, $page->getHeight()-$line_nr);
            $line_nr += $font_sz + $line_space;
    
            if ($line_nr > $page->getHeight()) {
                $pdf->pages[$page_nr] = $page;
                $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
                $page->setFont($font, $font_sz);
                $page_nr ++;
                $line_nr = 0;
            }
        }
       
        if ($line_nr > 0) {
           $pdf->pages[$page_nr] = $page;
        }
     
        $pdf->save('message.pdf');
        echo 'SUCCESS: Document saved!';

    } catch (Zend_Pdf_Exception $e) {
        die ('PDF error: ' . $e->getMessage());  
    } catch (Exception $e) {
        die ('Application error: ' . $e->getMessage());    
    }
?>
