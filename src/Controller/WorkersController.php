<?php

namespace App\Controller;

use App\Entity\Workers;
use App\Form\WorkersType;
use App\Repository\WorkersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/workers")
 */
class WorkersController extends AbstractController
{
    /**
     * @Route("/{page}/{order}", name="workers_index", requirements={"page"="\d+"}, methods={"GET", "POST"})
     */
    public function index(WorkersRepository $workersRepository, $page = 1, $order = "id"): Response
    {

        $workersArray = $workersRepository->getWorkersByPage($page, $order);

        $pages = [];
        if($page + 3 <= $workersArray[2] ){
            $pages = [$page];
            $pages[] = $page + 1;

            $pages[] = $workersArray[2] - 1;
            $pages[] = $workersArray[2];
        }
        else if($page + 2 == $workersArray[2]){
            $pages = [1];
            $pages[] = $page;

            $pages[] = $workersArray[2] - 1;
            $pages[] = $workersArray[2];
        }
        else
        {
            $pages = [1];
            $pages[] =2;

            $pages[] = $workersArray[2] - 1;
            $pages[] = $workersArray[2];
        }

        return $this->render('workers/index.html.twig', [
            'workers' => $workersArray[0],
            'totalItems'=> $workersArray[1],
            'pageCount' => $workersArray[2],
            'pages' => $pages,
            'currentPage' => $page,
            'order' => $order
        ]);
    }





    /**
     * @Route("/new", name="workers_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $worker = new Workers();
        $form = $this->createForm(WorkersType::class, $worker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($worker);
            $entityManager->flush();

            return $this->redirectToRoute('workers_index');
        }

        return $this->render('workers/new.html.twig', [
            'worker' => $worker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="workers_show", methods={"GET"})
     */
    public function show(Workers $worker): Response
    {
        return $this->render('workers/show.html.twig', [
            'worker' => $worker,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="workers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Workers $worker): Response
    {
        $form = $this->createForm(WorkersType::class, $worker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('workers_index');
        }

        return $this->render('workers/edit.html.twig', [
            'worker' => $worker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="workers_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Workers $worker): Response
    {
        if ($this->isCsrfTokenValid('delete'.$worker->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($worker);
            $entityManager->flush();
        }

        return $this->redirectToRoute('workers_index');
    }
}
