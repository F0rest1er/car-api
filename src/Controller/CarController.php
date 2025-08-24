<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
class CarController extends AbstractController
{
    #[Route('/cars', name: 'api_cars_index', methods: ['GET'])]
    public function index(CarRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $cars = $repo->createQueryBuilder('c')
            ->addSelect('b')
            ->join('c.brand', 'b')
            ->getQuery()->getResult();

        $json = $serializer->serialize($cars, 'json', ['groups' => ['car:list']]);
        return JsonResponse::fromJsonString($json);
    }

    #[Route('/cars/{id}', name: 'api_cars_show', methods: ['GET'])]
    public function show(Car $car, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($car, 'json', ['groups' => ['car:detail']]);
        return JsonResponse::fromJsonString($json);
    }
}
