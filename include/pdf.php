<?php
  //Now generate pdf from html
    include("../mpdf60/mpdf.php");

    //A4 paper
    $mpdf = new mPDF('utf-8' , "A4");// , '' , '' , 50 , 1 , 1 , 1 , 1 , 1);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

    $mpdf->WriteHTML("<p style=\"font-family:'DejaVuSans';\">
        CIAO!
    </p>");
    $mpdf->Output("proca.pdf", "D");

?>
