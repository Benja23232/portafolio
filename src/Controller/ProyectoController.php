<?php

namespace App\Controller;

use App\Entity\Proyecto;
use App\Entity\Imagen;
use App\Form\ProyectoForm;
use App\Repository\ProyectoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proyecto')]
final class ProyectoController extends AbstractController
{
    #[Route(name: 'app_proyecto_index', methods: ['GET'])]
    public function index(ProyectoRepository $proyectoRepository): Response
    {
        return $this->render('proyecto/index.html.twig', [
            'proyectos' => $proyectoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_proyecto_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    // VERIFICACIÓN DE ACCESO - Agregar estas líneas
    $clave = $request->query->get('clave');
    if ($clave !== 'mi_clave_secreta') {
        $this->addFlash('error', 'Acceso no autorizado para crear proyectos.');
        return $this->redirectToRoute('app_inicio');
    }

    // TODO TU CÓDIGO ACTUAL (igual que antes)
    $proyecto = new Proyecto();
    $form = $this->createForm(ProyectoForm::class, $proyecto);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // Procesar múltiples imágenes subidas
        $files = $form->get('imagens')->getData(); // array de UploadedFile
        if ($files) {
            $uploadDir = $this->getParameter('kernel.project_dir').'/public/uploads/proyectos';
            foreach ($files as $file) {
                if (!$file) { continue; }
                $filename = uniqid('', true).'.'.$file->guessExtension();
                $file->move($uploadDir, $filename);

                $imagen = new Imagen();
                $imagen->setArchivo($filename); // guardamos el nombre del archivo
                $proyecto->addImagen($imagen);
            }
        }

        $entityManager->persist($proyecto);
        $entityManager->flush();

        return $this->redirectToRoute('app_proyecto_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('proyecto/new.html.twig', [
        'proyecto' => $proyecto,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_proyecto_show', methods: ['GET'])]
    public function show(Proyecto $proyecto): Response
    {
        return $this->render('proyecto/show.html.twig', [
            'proyecto' => $proyecto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proyecto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Proyecto $proyecto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProyectoForm::class, $proyecto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Procesar nuevas imágenes subidas en edición
            $files = $form->get('imagens')->getData();
            if ($files) {
                $uploadDir = $this->getParameter('kernel.project_dir').'/public/uploads/proyectos';
                foreach ($files as $file) {
                    if (!$file) { continue; }
                    $filename = uniqid('', true).'.'.$file->guessExtension();
                    $file->move($uploadDir, $filename);

                    $imagen = new Imagen();
                    $imagen->setArchivo($filename);
                    $proyecto->addImagen($imagen);
                }
            }

            $entityManager->flush();
            
            $this->addFlash('success','Exito!');

            return $this->redirectToRoute('app_proyecto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('proyecto/edit.html.twig', [
            'proyecto' => $proyecto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proyecto_delete', methods: ['POST'])]
    public function delete(Request $request, Proyecto $proyecto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proyecto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($proyecto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proyecto_index', [], Response::HTTP_SEE_OTHER);
    }
}
