<?php

namespace App\Controller;

use App\Repository\WorkersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
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

        return $this->render('home/index.html.twig', [
            'workers' => $workersArray[0],
            'totalItems'=> $workersArray[1],
            'pageCount' => $workersArray[2],
            'pages' => $pages,
            'currentPage' => $page,
            'order' => $order
        ]);
    }
}
