<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ItemRepository $items, UserRepository $users): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'lowStock' => $items->findLowStock(),
            'staff' => $this->isGranted('ROLE_MANAGER')
                ? $users->findBy([], ['fullname' => 'ASC'])
                : [],
        ]);
    }
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }
}