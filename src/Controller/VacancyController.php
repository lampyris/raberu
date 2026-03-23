<?php

namespace App\Controller;

use App\Entity\Application;
use App\Repository\VacancyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacancyController extends AbstractController
{
    #[Route('/api/vacancies/{id}/apply', name: 'api_vacancy_apply', methods: ['POST'])]
    public function apply(
        int $id,
        VacancyRepository $vacancyRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        // ВРЕМЕННО: берем первого пользователя
        $user = $em->getRepository(\App\Entity\User::class)->find(1);

        if (!$user) {
            return $this->json(['error' => 'Run fixtures first'], 500);
        }

        $vacancy = $vacancyRepository->find($id);

        if (!$vacancy) {
            return $this->json(['error' => 'Vacancy not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$vacancy->isActive()) {
            return $this->json(['error' => 'Vacancy is not active'], Response::HTTP_BAD_REQUEST);
        }

        $existing = $em->getRepository(Application::class)->findOneBy([
            'user' => $user,
            'vacancy' => $vacancy
        ]);

        if ($existing) {
            return $this->json(['error' => 'Already applied'], Response::HTTP_BAD_REQUEST);
        }

        $application = new Application();
        $application->setUser($user);
        $application->setVacancy($vacancy);
        $application->setCreatedAt(new \DateTimeImmutable());

        $em->persist($application);
        $em->flush();

        return $this->json(['status' => 'ok']);
    }
}
