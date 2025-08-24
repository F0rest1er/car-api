<?php

namespace App\Controller;

use App\Entity\CreditRequest;
use App\Repository\CarRepository;
use App\Repository\CreditProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class CreditRequestController extends AbstractController
{
    #[Route('/request', name: 'api_request_create', methods: ['POST'])]
    public function create(
        Request $request,
        CarRepository $carRepo,
        CreditProgramRepository $programRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent() ?: '{}', true);

        $carId = (int) ($data['carId'] ?? 0);
        $programId = (int) ($data['programId'] ?? 0);

        $initialRaw = isset($data['initialPayment']) ? (string) $data['initialPayment'] : '0';
        $initialNormalized = str_replace(',', '.', $initialRaw);
        if (!is_numeric($initialNormalized)) {
            return $this->json(['success' => false, 'error' => 'Invalid initialPayment'], 400);
        }

        $loanTerm = (int) ($data['loanTerm'] ?? 0);

        if ($carId <= 0 || $programId <= 0 || $loanTerm <= 0) {
            return $this->json(['success' => false, 'error' => 'Invalid payload'], 400);
        }

        $car = $carRepo->find($carId);
        $program = $programRepo->find($programId);
        if (!$car || !$program) {
            return $this->json(['success' => false, 'error' => 'Car or Program not found'], 404);
        }

        $req = new CreditRequest();
        $req->setCar($car)
            ->setProgram($program)
            ->setInitialPayment(number_format((float)$initialNormalized, 2, '.', ''))
            ->setLoanTerm($loanTerm);

        $em->persist($req);
        $em->flush();

        return $this->json(['success' => true]);
    }
}
