<?php

namespace App\Controller\Impression;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Accès refusé. Espace reservé uniquement aux abonnés")
 */
class ImpressionController extends AbstractController
{
    #[Route('/impression', name: 'impression')]
    public function impression(Request $request, TranslatorInterface $translator): Response
    {
        # je récupère ma session
        $maSession = $request->getSession();

        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_logout");
        }

        #je supprime mes variables de la session
        $maSession->set('ajout', null) ;
        $maSession->set('misAjour', null);
        $maSession->set('suppression', null) ;

    
        return $this->render('impression/impression.html.twig', [
            'licence' => 1,
            'dossier' => $translator->trans("Impression"),
            'route' => $translator->trans("Imprimer certains documents")
            
        ]);
    }
}
