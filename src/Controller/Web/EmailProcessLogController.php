<?php

namespace App\Controller\Web;

use App\Entity\EmailProcessLog;
use App\Form\EmailProcessLogType;
use App\Repository\EmailProcessLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/email/process/log')]
final class EmailProcessLogController extends AbstractController
{
    #[Route(name: 'app_email_process_log_index', methods: ['GET'])]
    public function index(EmailProcessLogRepository $emailProcessLogRepository): Response
    {
        return $this->render('email_process_log/index.html.twig', [
            'email_process_logs' => $emailProcessLogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_email_process_log_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emailProcessLog = new EmailProcessLog();
        $form = $this->createForm(EmailProcessLogType::class, $emailProcessLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emailProcessLog);
            $entityManager->flush();

            return $this->redirectToRoute('app_email_process_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('email_process_log/new.html.twig', [
            'email_process_log' => $emailProcessLog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_process_log_show', methods: ['GET'])]
    public function show(EmailProcessLog $emailProcessLog): Response
    {
        return $this->render('email_process_log/show.html.twig', [
            'email_process_log' => $emailProcessLog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_email_process_log_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmailProcessLog $emailProcessLog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmailProcessLogType::class, $emailProcessLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_email_process_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('email_process_log/edit.html.twig', [
            'email_process_log' => $emailProcessLog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_process_log_delete', methods: ['POST'])]
    public function delete(Request $request, EmailProcessLog $emailProcessLog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emailProcessLog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($emailProcessLog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_email_process_log_index', [], Response::HTTP_SEE_OTHER);
    }
}
