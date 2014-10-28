<?php
/* srcview.php
 *
 * Author: Zex <top_zlynch@yahoo.com>
 */

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();

function get_all_groups($curdir, $groups)
{
    $dirfd = dir($curdir);

    while (false !== ($entry = $dirfd->read())) {

        if (ereg("^\.", $entry))
            continue;

        if (is_dir($curdir."/".$entry))
            $groups += get_all_groups($curdir."/".$entry, $groups);
        else 
            array_push($groups, $curdir."/".$entry);
    }

    return $groups;
}

try {

    $pdf = new Zend_Pdf();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $font_sz = 10;
    $line_space = 1;
    $indent = 15;
    $page_space = $font_sz*4;
    $date_fmt =  "D M Y H:m:s";
    $now = date($date_fmt);

    $proj = "EasySipTs";
    $projver = "v123.44.567";
    $outfile = $proj."-".$projver;
    $dirpath = "EasySipTs";
    $groups = get_all_groups($dirpath, []);
    $srcs = [];


    foreach ($groups as $f) {
        if (ereg(".*\.[cpp|c|h]", $f)) {
            array_push($srcs, $f);
        }
    }

    $page_nr = 0;

    /* generate each file */
    foreach ($srcs as $filename) {

        $buf = file($filename);

        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
        $page->setFont($font, $font_sz);
        $limit_width = $page->getWidth()-$indent*2;
        $line_nr = $page_space;
        $page_nr ++;
        $pg_head = $now."  ".$outfile."-".$filename;
    
        /* generate project name */
        $page->drawText($pg_head, ($limit_width-strlen($pg_head))/4, $page->getHeight()-20);
        $page->drawText($page_nr, $limit_width, $page->getHeight()-20);
        $line_nr += ($font_sz + $line_space)*2;

//        /* generate file name */
//        $page->drawText($filename, $indent/2, $page->getHeight()-$line_nr);
//        $line_nr += $font_sz + $line_space*2;

        /* generate each page */
        foreach ($buf as $nr => $line) {

            $ind = 0;

            /* generate each line */
            while ($ind < strlen($line)) {

                $page->drawText(substr($line, $ind, $limit_width), $indent, $page->getHeight()-$line_nr);
                $line_nr += $font_sz + $line_space;
                $ind += $limit_width;

                if ($line_nr > $page->getHeight()-$page_space) {
    
                    $pdf->pages[$page_nr] = $page;
    
                    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
                    $page->setFont($font, $font_sz);
                    $line_nr = $page_space;
                    $page_nr ++;
                
                    /* generate project name */
                    $page->drawText($pg_head, ($limit_width-strlen($pg_head))/4, $page->getHeight()-20);
                    $page->drawText($page_nr, $limit_width, $page->getHeight()-20);
                    $line_nr += ($font_sz + $line_space)*2;
                }
            }
        }
    
        if ($line_nr > $page_space) {
            $pdf->pages[$page_nr] = $page;
        }
    }

    $pdf->save($outfile.".pdf");
    echo "srcview: ".$outfile.".pdf"." generated!\n";
    echo "total page: ".($page_nr+1)."\n";

} catch (Exception $e) {
    die ('Application error: ' . $e->getMessage());
}

?>
