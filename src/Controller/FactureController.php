<?php

namespace App\Controller;

use App\Dto\EtudeDto;
use App\Repository\AchatRepository;
use App\Repository\AdherentRepository;
use App\Service\CotisationService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\NotBlank;

class FactureController extends AbstractController
{
    #[Route('/invoices', name: 'app_facture')]
    public function index(Request $request, ValidatorInterface $validator, AdherentRepository $adherentRepo, AchatRepository $achatRepository, CotisationService $cotisationService): JsonResponse
    {
        $beginStr = $request->get('begin');
        $codeAdherent = $request->get('adherent');
        $errors = $validator->validate(
            $beginStr, 
            [ new NotBlank(), new Constraints\Date() ]
        );

        if (count($errors) > 0) {    
            return $this->json($errors, 400);
        }

        $begin = DateTime::createFromFormat('Y-m-d', $beginStr);
        $adherent = $adherentRepo->find($codeAdherent);


        if($adherent == null) {
            return $this->json('Adherent not found', 404);
        }

        $achats = $achatRepository->findByAdherent($adherent);

        $total = 0;
        $negociateTotal = 0;

        foreach ($achats as &$achat) {
            $total = $total + $achat->getCurrentPrice() * $achat->getQuantity();
            $negociateTotal = $negociateTotal + $achat->getNegociatePrice() * $achat->getQuantity();
        }


        $cotisation = $cotisationService->compute($adherent, $total);

        return $this->json(new EtudeDto($total, $negociateTotal, $cotisation));
    }
}
