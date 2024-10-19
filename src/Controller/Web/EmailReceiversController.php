<?php

namespace App\Controller\Web;

use App\Entity\EmailReceivers;
use App\Form\EmailReceiversType;
use App\Repository\EmailReceiversRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/email/receivers')]
final class EmailReceiversController extends AbstractController
{
    #[Route(name: 'app_email_receivers_index', methods: ['GET'])]
    public function index(EmailReceiversRepository $emailReceiversRepository): Response
    {
        return $this->render('email_receivers/index.html.twig', [
            'email_receivers' => $emailReceiversRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_email_receivers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emailReceiver = new EmailReceivers();
        $form = $this->createForm(EmailReceiversType::class, $emailReceiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emailReceiver);
            $entityManager->flush();

            return $this->redirectToRoute('app_email_receivers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('email_receivers/new.html.twig', [
            'email_receiver' => $emailReceiver,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_receivers_show', methods: ['GET'])]
    public function show(EmailReceivers $emailReceiver): Response
    {
        return $this->render('email_receivers/show.html.twig', [
            'email_receiver' => $emailReceiver,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_email_receivers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmailReceivers $emailReceiver, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmailReceiversType::class, $emailReceiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_email_receivers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('email_receivers/edit.html.twig', [
            'email_receiver' => $emailReceiver,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_receivers_delete', methods: ['POST'])]
    public function delete(Request $request, EmailReceivers $emailReceiver, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emailReceiver->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($emailReceiver);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_email_receivers_index', [], Response::HTTP_SEE_OTHER);
    }
}
