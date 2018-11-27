<?php
include("../lib/mpdf/mpdf.php");
include '../Autoload.php';
$Autoload = new Autoload;
$Autoload->sessionProfessor();
$API = new consumo_api;
$projeto= new projeto;
$mpdf=new mPDF('c', 'A4', '', '', 30, 20,30,20);     

$mpdf->AddPage();
        

$acao     = (isset($_REQUEST['acao']))    ? $_REQUEST['acao']     : false;
$idItem   = (isset($_REQUEST['idItem']))  ? $_REQUEST['idItem']   : false;
$idSessao = (isset($_REQUEST['idSessao']))? $_REQUEST['idSessao'] : false;

if ($acao && $idItem && $idSessao) {
    if ($acao == 'selecionarSessaoItem') {
        $dadosItemSessao = $projeto->selecionarSessaoItem($idSessao, $idItem);
        $lista = $dadosItemSessao[0];

        $capa = "
                <html>
                    <body>
                        <img src='/unisepe/sisproj/assets/img/capa-unisepe.png' style='margin: 0 auto;'>

                        <h3 style='margin-top:250px'>$lista->titulo</h3>
                    </body>
                </html>
                ";
        $mpdf->writeHTML($capa);
        $rodape = '<p style="text-align:center">Registro<br>'.date('Y').'</p>';
        $mpdf->setFooter($rodape);

        $header = '
            <table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
            <td width="33%">'.$lista->titulo.' <span style="font-size:14pt;"></span></td>
            <td width="33%" align="center"><img src="/unisepe/sisproj/assets/img/unisepe.png" width="126px" /></td>
            <td width="33%" style="text-align: right;"><span style="font-weight: bold;">{PAGENO}</span></td>
            </tr></table>
            ';
        $mpdf->SetHTMLHeader($header);
        $mpdf->AddPage();
        $html = "<html>";
        $html .= "<header>";
        $html .= "<style>
                html {
                  color: #000;
                  padding: 3cm 2cm 2cm 3cm;

                }
                body {
                  font-family: Arial;
                  font-size: 12pt;

                }
                h1, h2, h3, h4, h5, h6 {
                  font-family: Arial;
                  font-style: normal;
                  margin: 3pt 0 3pt 0;
                }
                h1 {
                  font-size: 14pt;
                }
                h2, h3, h4, h5, h6 {
                  font-size: 12pt;
                  font-weight: normal;
                }
                p {
                  margin: 0;
                  font-family: Arial;
                  font-size: 12pt;
                  text-indent: 1.5cm;
                  text-align: justify;
                  line-height: 150%;
                }
                ul {
                  margin-left: 1.5cm;
                }
                p, ul {
                  font-family: Arial;
                  font-size: 12pt;
                  line-height: 150%;
                  text-align: justify;
                }
                blockquote {
                  border: none;
                  font-size: 10pt;
                  line-height: 100%;
                  margin-left: 4cm;
                  text-align: justify;
                }
                table{
                    width: 100%;
                }
               </style>";
        $html .= "</header>";
        $html .= "<body>";
        $html .= $lista->texto;
        $html .= "</body>";
        $html .= "</html>";

        $mpdf->SetHTMLHeader($header);
        $mpdf->setFooter($lista->nome.'||{PAGENO} de {nbpg} páginas') ;
        $mpdf->writeHTML($html);
            
    }
}
$mpdf->Output($lista->nome.'.pdf','I');
exit;




?>