 <?php
include plugin_dir_path(__FILE__)."tfpdf/tfpdf.php";


class PDF extends TFPDF
{
// Page header
function Header()
{
    // Logo
    // $this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);

$this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$this->SetFont('DejaVu','',14);

    // Move to the right
    $this->Cell(30);
    // Title
    $this->Cell(30,10,'ФАКТУРА - ОРИГИНАЛ',1,0,'C');
    $this->Cell(80);
    $this->Cell(30,10,'ИМЕ НА ФИРМАТА',1,0,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    $this->SetY(-15);
    $this->SetFont('Arial','B',10);
    $this->Cell(0, 10, $this->PageNo(), 0, 0, 'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
// $pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
for($i = 1; $i <= 5; $i++)
    $pdf->Cell(0,10,'Printing line number '.$i,0,1);

$post = get_post( $post_id );
$chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
$company_name = get_post_meta( $chosen_client, 'company_name', true );

$pdf->Cell(40, 10, get_post_meta( $chosen_client, 'company_name', true ));
$pdf->Cell(40, 10, get_post_meta( $chosen_client, 'company_id', true ));

$pdf->Output();

// $pdf->Cell(40,5,' ','LTR',0,'L',0);   // empty cell with left,top, and right borders
// $pdf->Cell(50,5,'Words Here',1,0,'L',0);
// $pdf->Cell(50,5,'Words Here',1,0,'L',0);
// $pdf->Cell(40,5,'Words Here','LR',1,'C',0);  // cell with left and right borders
// $pdf->Cell(50,5,'[ x ] abc',1,0,'L',0);
// $pdf->Cell(50,5,'[ x ] checkbox1',1,0,'L',0);
// $pdf->Cell(40,5,'','LBR',1,'L',0);   // empty cell with left,bottom, and right borders
// $pdf->Cell(50,5,'[ x ] def',1,0,'L',0);
// $pdf->Cell(50,5,'[ x ] checkbox2',1,0,'L',0);
// $pdf->Output();
?>