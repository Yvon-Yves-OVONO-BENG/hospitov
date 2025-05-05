<?php

namespace App\Controller\Modele;

use App\Repository\MarqueRepository;
use App\Repository\ModeleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheModeleController extends AbstractController
{
    public function __construct(
        protected MarqueRepository $marqueRepository, 
        protected ModeleRepository $modeleRepository, 
        )
    {}

    #[Route('/recherche-modele', name: 'recherche_modele')]
    public function rechercheModele(Request $request): JsonResponse
    {
        $modeles = $this->modeleRepository->findBy([
            'marque' => $this->marqueRepository->find((int)$request->request->get('id'))
        ],[
            'modele' => 'DESC'
        ]);
        
        $dataTmp =  array();
        if ($modeles) {
            foreach ($modeles as $modele) {
                $dataTmp[] = [
                    'id' => $modele->getId(),
                    'modele' => $modele->getModele()
                ];
            }
            $json = new JsonResponse($dataTmp);
            return $json;
        }else {
            $json = new JsonResponse($dataTmp);
            return $json;
        }
    }
}
