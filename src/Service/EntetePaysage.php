<?php

namespace App\Service;

use Exception;
use Fpdf\Fpdf;

class EntetePaysage
{
	public function __construct()
	{
	}

	public function entetePaysage(Fpdf $pdf): Fpdf
	{
		// $logoPath = __DIR__.'/../../images/logo/logoLucelle.png';
		// $arrierePlan = __DIR__.'/../../images/logo/arrierePlan.png';
		// if (file_exists($logoPath)) 
		// {
		// 	$pdf->Image($logoPath, 130, 12, 25);
		// 	$pdf->Image($arrierePlan, 190, 90, 150);
		// } 
		// else 
		// {
		// 	throw new Exception("Image non trouvée dans :  $logoPath ");
		// }
		
		
		
		$pdf->SetFont('Helvetica', 'B', 12);
		// fond de couleur gris (valeurs en RGB)
		$pdf->setFillColor(230, 230, 230);
		// position du coin supérieur gauche par rapport à la marge gauche (mm)

		#################################

		$pdf->SetX(20);
		$pdf->Cell(70, 4, utf8_decode("CENTRE DE SANTE PRIVE LUCELLE"), 0, 0, 'C', 0);
		$pdf->Cell(120, 4, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 4, utf8_decode('LUCELLE PRIVATE HEALTH CENTER'), 0, 1, 'C', 0);

		$pdf->SetX(20);
		$pdf->SetFont('Helvetica', 'B', 8);
		$pdf->Cell(70, 2, '*********', 0, 0, 'C', 0);
		$pdf->Cell(119, 2, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 2, '*********', 0, 1, 'C', 0);

		################################
		$pdf->SetX(20);
		$pdf->Cell(70, 4, utf8_decode('670 57 03 40 / 683 80 62 67 / 687 36 53 15'), 0, 0, 'C', 0);
		$pdf->Cell(120, 4, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 4, utf8_decode('670 57 03 40 / 683 80 62 67 / 687 36 53 15'), 0, 1, 'C', 0);

		$pdf->SetX(20);
		$pdf->SetFont('Helvetica', 'B', 8);
		$pdf->Cell(70, 2, '*********', 0, 0, 'C', 0);
		$pdf->Cell(119, 2, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 2, '*********', 0, 1, 'C', 0);

		################################
		$pdf->SetX(20);
		$pdf->Cell(70, 4, utf8_decode('BP : '), 0, 0, 'C', 0);
		$pdf->Cell(120, 4, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 4, utf8_decode('Po.Box : '), 0, 1, 'C', 0);

		$pdf->SetX(20);
		$pdf->SetFont('Helvetica', 'B', 8);
		$pdf->Cell(70, 2, '*********', 0, 0, 'C', 0);
		$pdf->Cell(119, 2, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 2, '*********', 0, 1, 'C', 0);


		return $pdf;
	}
}
