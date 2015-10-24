 <?php
include plugin_dir_path(__FILE__)."fpdf/fpdf.php";

$post = get_post( $post_id );
$chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
$company_name = get_post_meta( $chosen_client, 'company_name', true );


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40, 10, get_post_meta( $chosen_client, 'company_name', true ));
$pdf->Cell(40, 30, get_post_meta( $chosen_client, 'company_id', true ));
$pdf->Output();
?>