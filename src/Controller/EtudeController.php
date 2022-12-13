<?php

namespace App\Controller;

use App\Dto\EtudeDTO;
use App\Repository\AdherentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EtudeController extends AbstractController
{
    #[Route('/etude', name: 'app_etude')]
    public function index(Request $request, ValidatorInterface $validator, AdherentRepository $adherentRepository): JsonResponse
    {
        $codeAdherent = $request->get('adherent');

        $errors = $validator->validate($codeAdherent, [new NotNull()]);

        if(count($errors)) {
            return $this->json('Parameter incorrect', 400);
        }

        $adherent = $adherentRepository->findByIdWithAchat($codeAdherent);

        if(!$adherent) {
            return $this->json('Adherent not found', 404);
        }

        $total = 0;
        $totalNegociate = 0;
        foreach ($adherent->getAchats() as &$achat) {
            $total += $achat->getCurrentPrice() * $achat->getQuantity();
            $totalNegociate += $achat->getNegociatePrice() * $achat->getQuantity();
        }

        $cotisation = $adherent->getCotisation();
        if ($totalNegociate > 5000) {
            $cotisation = $cotisation * 0.8;
        } elseif($totalNegociate > 1000) {
            $cotisation = $cotisation * 0.9;
        }

        return $this->json(new EtudeDTO($total, $totalNegociate, 10, $cotisation, -90));
    }
}
