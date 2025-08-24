<?php

namespace App\Controller;

use App\Repository\CreditProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class CreditController extends AbstractController
{
    #[Route('/credit/calculate', name: 'api_credit_calculate', methods: ['GET'])]
    public function calculate(Request $request, CreditProgramRepository $programsRepo): JsonResponse
    {
        $price = (int) $request->query->get('price', 0);

        $initialRaw = (string) $request->query->get('initialPayment', '0');
        $initialPayment = (float) str_replace(',', '.', $initialRaw);

        $loanTerm = (int) $request->query->get('loanTerm', 0);

        if ($price <= 0 || $loanTerm <= 0) {
            return $this->json(['error' => 'Invalid price or loanTerm'], 400);
        }

        $principal = max(0.0, $price - $initialPayment);

        $programs = $programsRepo->findBy([], ['interestRate' => 'ASC']);
        if (!$programs) {
            return $this->json(['error' => 'No credit programs'], 500);
        }

        $selected = null;
        $selectedPayment = null;

        foreach ($programs as $p) {
            $rateAnnual = $p->getInterestRate();
            $r = ($rateAnnual / 100.0) / 12.0;

            if ($principal <= 0) {
                $payment = 0;
            } else {
                $payment = (int) round(($principal * $r) / (1 - pow(1 + $r, -$loanTerm)));
            }

            $minInit = $p->getMinInitialPayment() !== null ? (float) $p->getMinInitialPayment() : null;
            if ($minInit !== null && $initialPayment < $minInit) {
                continue;
            }
            $maxPay = $p->getMaxMonthlyPayment();
            if ($maxPay !== null && $payment > $maxPay) {
                continue;
            }
            $maxTerm = $p->getMaxLoanTermMonths();
            if ($maxTerm !== null && $loanTerm > $maxTerm) {
                continue;
            }

            $selected = $p;
            $selectedPayment = $payment;
            break;
        }

        if (!$selected) {
            $p = $programs[0];
            $r = ($p->getInterestRate() / 100.0) / 12.0;
            $selectedPayment = $principal > 0 ? (int) round(($principal * $r) / (1 - pow(1 + $r, -$loanTerm))) : 0;
            $selected = $p;
        }

        return $this->json([
            'programId'     => $selected->getId(),
            'interestRate'  => round($selected->getInterestRate(), 1),
            'monthlyPayment'=> $selectedPayment,
            'title'         => $selected->getTitle(),
        ]);
    }
}
