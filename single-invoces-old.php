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
    // $this->SetFont('Arial','B',15);

$this->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf',true);
$this->SetFont('DejaVu', '', 14);

    // Move to the right
    $this->Cell(30);
    // Title
    $this->Cell(30, 10, 'ФАКТУРА - ОРИГИНАЛ', 0, 0, 'R');
    $this->Cell(60);
    $chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
    $this->Cell(0, 10, get_post_meta( $chosen_client, 'company_name', true ), 0, 0, 'R');
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
// $pdf->SetFont('Times', '', 12);
// for($i = 1; $i <= 5; $i++)
//     $pdf->Cell(0, 10, 'Printing line number ' . $i, 0, 1);

$post = get_post( $post_id );
$chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
$company_name = get_post_meta( $chosen_client, 'company_name', true );
$invoiceID = get_the_ID();
$pdf->Cell(63,6,'ПОЛУЧАТЕЛ', 'LTRB', 0,'C',0);
$pdf->SetTextColor(194,6,6);
$pdf->SetFont('DejaVu', '', 20);
$pdf->Cell(63,20,'№00000000001','T', 0, 'C', 0);
$pdf->SetFont('DejaVu', '', 14);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(63,6,'ДОСТАВЧИК','LTRB',0,'C',0);
$pdf->Ln(6);
$pdf->SetFont('DejaVu', '', 12);
$pdf->Cell(63,6,'Име: '. get_post_meta( $chosen_client, 'company_name', true ),'LR',0,'L',0);
$pdf->Cell(63,6,' ','',0,'L',0);
$pdf->Cell(63,6,'Име: ','LR',0,'L',0);
$pdf->Ln(6);
$pdf->Cell(63,6,'Град: '. get_post_meta( $chosen_client, 'company_city', true ),'LR',0,'L',0);
$pdf->Cell(63,6,' ','',0,'',0);
$pdf->Cell(63,6,'Град: ','LR',0,'L',0);
$pdf->Ln(6);
$pdf->Cell(63,6,'Адрес: '. get_post_meta( $chosen_client, 'company_address', true ),'LR',0,'L',0);
$pdf->Cell(63,6,'Дата: '. get_post_meta( $invoiceID, 'chosen_date', true ),'',0,'C',0);
$pdf->Cell(63,6,'Адрес: ','LR',0,'L',0);
$pdf->Ln(6);
$pdf->Cell(63,6,'ЕИК: '. get_post_meta( $chosen_client, 'company_id', true ),'LR',0,'L',0);
$pdf->Cell(63,6,' ','',0,'L',0);
$pdf->Cell(63,6,'ЕИК: ','LR',0,'L',0);
$pdf->Ln(6);
$pdf->Cell(63,6,'МОЛ: '. get_post_meta( $chosen_client, 'responsible_person', true ),'LRB',0,'L',0);
$pdf->Cell(63,6,' ','B',0,'',0);
$pdf->Cell(63,6,'МОЛ: ','LRB',0,'L',0);
$pdf->Ln(6);
$pdf->Cell(10,6,'№','LRB',0,'L',0);
$pdf->Cell(80,6,'СТОКИ / УСЛУГИ','B',0,'',0);
$pdf->Cell(23,6,'КОЛ-ВО','LRB',0,'L',0);
$pdf->Cell(23,6,'МЯРКА','LRB',0,'L',0);
$pdf->Cell(23,6,'ЕД. ЦЕНА','LRB',0,'L',0);
$pdf->Cell(30,6,'СТОЙНОСТ','LRB',0,'L',0);
$pdf->Ln(6);
$invoice_item_column_number = get_post_meta( $invoiceID, 'invoice_item_column_number', true );
for($i = 0; $i <= $invoice_item_column_number; $i++) {
    $pdf->Cell(10,6, $i+1,'LRB',0,'L',0);
    $pdf->Cell(80,6, get_post_meta( $invoiceID, 'name'.$i, true ),'B',0,'',0);
    $pdf->Cell(23,6, get_post_meta( $invoiceID, 'quantity'.$i, true ),'LRB',0,'L',0);
    $pdf->Cell(23,6,'бр.','LRB',0,'L',0);
    $pdf->Cell(23,6, get_post_meta( $invoiceID, 'price'.$i, true ),'LRB',0,'L',0);
    $pdf->Cell(30,6,'СТОЙНОСТ','LRB',0,'L',0);
    $pdf->Ln(6);
}
 
$pdf->Output();


// $pdf->Ln(40);
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