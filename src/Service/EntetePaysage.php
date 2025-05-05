<?php

namespace App\Service;

use Fpdf\Fpdf;

class EntetePaysage
{
	public function __construct()
	{
	}

	public function entetePaysage(Fpdf $pdf): Fpdf
	{
		$pdf->Image('../public/assets/images/logoLucelle.png', 130, 12, 25);
		$pdf->Image('../public/images/logoLucelle.png', 190, 90, 150);
		$pdf->SetFont('Helvetica', 'B', 12);
		// fond de couleur gris (valeurs en RGB)
		$pdf->setFillColor(230, 230, 230);
		// position du coin supérieur gauche par rapport à la marge gauche (mm)

		#################################

		$pdf->SetX(20);
		$pdf->Cell(70, 4, utf8_decode("Centre de Santé Privé Lucelle"), 0, 0, 'C', 0);
		$pdf->Cell(120, 4, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 4, utf8_decode('Lucelle Private Health Center'), 0, 1, 'C', 0);

		$pdf->SetX(20);
		$pdf->SetFont('Helvetica', 'B', 8);
		$pdf->Cell(70, 2, '*********', 0, 0, 'C', 0);
		$pdf->Cell(119, 2, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 2, '*********', 0, 1, 'C', 0);

		################################
		$pdf->SetX(20);
		$pdf->Cell(70, 4, utf8_decode('BP : x- xx, Tel : 670 57 03 40 / 683 80 62 67 / 687 36 53 15'), 0, 0, 'C', 0);
		$pdf->Cell(120, 4, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 4, utf8_decode('Po.Box : x- xx, Tel : 670 57 03 40 / 683 80 62 67 / 687 36 53 15'), 0, 1, 'C', 0);

		$pdf->SetX(20);
		$pdf->SetFont('Helvetica', 'B', 8);
		$pdf->Cell(70, 2, '*********', 0, 0, 'C', 0);
		$pdf->Cell(119, 2, '', 0, 0, 'L', 0);
		$pdf->Cell(70, 2, '*********', 0, 1, 'C', 0);


		return $pdf;
	}
}
