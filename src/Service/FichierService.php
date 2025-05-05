<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use ZipArchive;
use App\Repository\FichierResultatExamenRepository;
use App\Repository\ResultatExamenRepository;

class FichierService
{
	public function __construct(
		// private string $projectDir,
		private ResultatExamenRepository $resultatExamenRepository,
		private FichierResultatExamenRepository $fichierResultatExamenRepository,
	)
	{}

	public function telechargerFichieResultat($slug)
	{
		#je récupère le résultatExamen
		$resultatExamen = $this->resultatExamenRepository->findOneBySlug([
			'slug' => $slug
		]);

		#je récupère les FichierService
		$fichiersExamenResultats = $this->fichierResultatExamenRepository->findBy([
			'resultatExamen' => $resultatExamen
		]);
		
		#je crée un fichier ZipArchive
		$zip = new ZipArchive();

		#le nom du fichier zip
		$zipName = "Résultats des examens de ".($resultatExamen->getBilletDeSession() ? str_replace(' ', '_', $resultatExamen->getBilletDeSession()->getPatient()->getNom()) : str_replace(' ', '_',$resultatExamen->getPatient()->getNom())).'.zip';
		
		$zip->open($zipName, ZipArchive::CREATE);
		
		foreach ($fichiersExamenResultats as $fichier) 
		{
			$filePath = '../public/fichiersExamens/'. $fichier->getFichierResultat();
			
			if (file_exists($filePath)) 
			{
				$zip->addFile($filePath, $fichier->getFichierResultat());
			} 
		}

		$zip->close();

		#je propose le zip en téléchargement
		$response = new Response();

		$response->setContent(file_get_contents($zipName));

		$response->headers->set('Content-Type', 'application/zip');

		$response->headers->set('Content-Disposition', 'attachment; filename="'.basename($zipName).'"');

		$response->headers->set('Content-Length', filesize($zipName));

		return $response;
	}
}
