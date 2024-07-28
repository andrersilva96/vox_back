<?php

namespace App\Controller;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

#[Route('/api/companies', name: 'company_')]
class CompanyController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $data = $this->entityManager->getRepository(Company::class)->findAll();
        return !$data
            ? $this->json(['success' => false, 'message' => 'No content.'], Response::HTTP_NOT_FOUND)
            : $this->json($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $company = new Company();
        $company->setName($data['name']);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return $this->json($company);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $company = $this->entityManager->getRepository(Company::class)->find($id);
        return $company
            ? $this->json($company)
            : $this->json(['success' => false, 'message' => 'No content.'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $company = $this->entityManager->getRepository(Company::class)->find($id);
        if (!$company) return $this->json(['success' => false, 'message' => 'No content.'], Response::HTTP_NOT_FOUND);

        $company->setName($data['name']);
        $this->entityManager->flush();

        return $this->json($company);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $company = $this->entityManager->getRepository(Company::class)->find($id);

        if ($company) {
            $this->entityManager->remove($company);
            $this->entityManager->flush();
        }

        return $this->json(['message' => 'Company deleted']);
    }
}
