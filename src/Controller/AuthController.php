<?php

// src/Controller/AuthController.php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $data['password'])
        );

        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'User successfully registered',
        ], Response::HTTP_CREATED);
    }
}
