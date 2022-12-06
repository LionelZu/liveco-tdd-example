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
        $codeAdherent = $request->get('adherent');

        if($result = $this->validateCodeAdherent($validator, $codeAdherent)) {
            return $result;
        }

        if(($adherent = $adherentRepo->findByIdWithAchat($codeAdherent)) == null) {
            return $this->json('Adherent not found', 404);
        }

        $total = $adherent->computeTotal();
        $negociateTotal = $adherent->computeNegociateTotal();
        $cotisation = $cotisationService->compute($adherent, $negociateTotal);

        return $this->json(new EtudeDto($total, $negociateTotal, $cotisation));
    }

    private function validateCodeAdherent(ValidatorInterface $validator, ?string $codeAdherent) {
        $errors = $validator->validate(
            $codeAdherent, 
            [ new NotBlank() ]
        );

        if (count($errors) > 0) {    
            return $this->json($errors, 400);
        }
    }
}
