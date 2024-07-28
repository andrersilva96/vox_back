<?php

namespace App\Controller;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/companies', name: 'company_')]
class CompanyController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $this->json($this->entityManager->getRepository(Company::class)->findAll());
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $company = new Company();
        $company->setName($data['name']);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return $this->json($company);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $company = $this->entityManager->getRepository(Company::class)->find($id);
        if (!$company) return $this->json(['message' => 'Company not found'], 404);

        return $this->json($company);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $company = $this->entityManager->getRepository(Company::class)->find($id);
        if (!$company) return $this->json(['message' => 'Company not found'], 404);

        $company->setName($data['name']);
        $this->entityManager->flush();

        return $this->json($company);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $company = $this->entityManager->getRepository(Company::class)->find($id);

        if ($company) {
            $this->entityManager->remove($company);
            $this->entityManager->flush();
        }

        return $this->json(['message' => 'Company deleted']);
    }
}
