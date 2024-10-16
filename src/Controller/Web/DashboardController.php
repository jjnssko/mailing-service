<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class DashboardController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
    public function adminLteDemo(): Response
    {
        return $this->render('admin-lte.html.twig');
    }
}
