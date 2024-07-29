<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

#[Route('/api/users', name: 'user_')]
class UserController extends AbstractController
{
    private $userRepository;
    private $entityManager;
    private $serializer;
    private $validator;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $arr = ['success' => true, 'data' => []];
        $arr['data'] = $this->entityManager->getRepository(User::class)->findAll();
        return !$arr['data']
            ? $this->json(['success' => false, 'message' => 'No content.'], Response::HTTP_NOT_FOUND)
            : $this->json($arr);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        return $user
            ? $this->json(['success' => true, 'data' => $user])
            : $this->json(['success' => false, 'message' => 'No content.'], Response::HTTP_NOT_FOUND);
    }


    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) return $this->json(['success' => false, 'message' => 'No content.'], Response::HTTP_NOT_FOUND);

        $this->serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new BadRequestException($errorsString);
        }

        $this->entityManager->flush();

        $data = $this->serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return $this->json(['success' => true, 'data' => $data]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) return $this->json(['success' => false, 'message' => 'No content.'], Response::HTTP_NOT_FOUND);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json(['true' => true, 'message' => 'The data has been deleted!']);
    }
}
