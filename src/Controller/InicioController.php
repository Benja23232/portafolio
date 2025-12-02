<?php

namespace App\Controller;

use App\Repository\ProyectoRepository;
use App\Form\ContactoForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class InicioController extends AbstractController
{
    #[Route('/', name: 'app_inicio')]
    public function index(ProyectoRepository $proyectoRepository, Request $request, MailerInterface $mailer): Response 
    {
        $proyectos = $proyectoRepository->findAll();
        $form = $this->createForm(ContactoForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            try {
                // USAR EMAIL VERIFICADO EN BREVO COMO REMITENTE
                $email = (new Email())
                    ->from('benjaarncivia@gmail.com') // Email verificado en Brevo
                    ->to('benjaarncivia@gmail.com') // Donde quieres recibir
                    ->replyTo($data['email']) // Para responder al contacto
                    ->subject('Mensaje del portafolio: ' . $data['asunto'])
                    ->html($this->renderView('emails/contact.html.twig', [
                        'nombre' => $data['nombre'],
                        'email' => $data['email'],
                        'asunto' => $data['asunto'],
                        'mensaje' => $data['mensaje'],
                        'fecha' => new \DateTime()
                    ]));

                $mailer->send($email);

                $this->addFlash('success', '¡Mensaje enviado correctamente!');
                return $this->redirectToRoute('app_inicio', ['_fragment' => 'contacto']);

            } catch (\Exception $e) {
                $this->addFlash('error', 'Error: ' . $e->getMessage());
            }
        }

        return $this->render('inicio/index.html.twig', [
            'proyectos' => $proyectos,
            'contactForm' => $form->createView(),
        ]);
    }

    #[Route('/ver-email', name: 'ver_email')]
    public function verEmail(): Response
    {
        return $this->render('emails/contact.html.twig', [
            'nombre' => 'Ana García',
            'email' => 'ana@ejemplo.com',
            'asunto' => 'Me interesa tu trabajo',
            'mensaje' => 'Hola Benjamin, vi tu portafolio y me encantó tu trabajo. Me gustaría contactarte para un proyecto.',
            'fecha' => new \DateTime()
        ]);
    }
}