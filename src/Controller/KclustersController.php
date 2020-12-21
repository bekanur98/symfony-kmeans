<?php

namespace App\Controller;

use App\Entity\Kclusters;
use App\Form\KclustersType;
use App\Repository\KclustersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/kclusters")
 */
class KclustersController extends AbstractController
{
    /**
     * @Route("/", name="kclusters_index", methods={"GET"})
     */
    public function index(KclustersRepository $kclustersRepository): Response
    {
        return $this->render('kclusters/index.html.twig', [
            'kclusters' => $kclustersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/clustering", name="clustering", methods={"GET", "POST"})
     */
    public function clustering(KclustersRepository $kclustersRepository, Request $request): Response
    {
        $dimensions = ['Age' => 'age', 'Height' => 'height', 'Weight' => 'weight'];

        $form = $this->createFormBuilder(null)
            ->add('clusterCount', NumberType::class, array('label'=> 'Count of clusters'))
            ->add('repeatTime', NumberType::class, array('label'=>'Repeat time'))
            ->add('dimensions', ChoiceType::class,[
                'label' => 'Select pair of dimension',
                'placeholder' => 'Chose two dimension',
                'choices' => $dimensions,
                'multiple' => true,
                'expanded' => true
            ]
            )
            ->add('cluster', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dd(1);
            if(sizeof($form->getData()['dimensions']) <= 1 || sizeof($form->getData()['dimensions']) == 3){
                $this->get('session')->getFlashBag()->add(
                    'warning',
                    'warning'
                );
                return $this->render('kclusters/clustering.html.twig',[
                        'msg' => 'Please choose two dimension',
                        'form' => $form->createView()
                    ]);
            }
            $clusterCount = (int)$form->getData()['clusterCount'];
            $repeatTime = (int)$form->getData()['repeatTime'];
            $firstDim = $form->getData()['dimensions'][0];
            $secondDim = $form->getData()['dimensions'][1];

            $msg = $this->callProcedure($clusterCount, $repeatTime, $firstDim, $secondDim);

            $this->get('session')->getFlashBag()->add(
                'success',
                'success'
            );
            return $this->render('kclusters/clustering.html.twig',[
                'form'=>$form->createView(),
                'msg' => $msg
            ]);
        }

        return $this->render('kclusters/clustering.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    public function callProcedure($clusterCount, $repeatTime, $firstDim, $secondDim){
        $conn = new \mysqli('localhost', 'root', '','aspnet') or die('Connect failed');
        //insert first random salary and age values
        $procPrepareData = "DELETE FROM kdata";
        $procPrepareCluters = "DELETE FROM kclusters";
        $conn->query($procPrepareData);
        $conn->query($procPrepareCluters);

        $procKDataInsert = "INSERT INTO kdata (id, first_dim, second_dim) SELECT id ,${firstDim}, ${secondDim} FROM workers where id < 3000;";
        $conn->query($procKDataInsert);

        $procInsert = "INSERT INTO kclusters (id, first_dim, second_dim) SELECT id, ${firstDim}, ${secondDim} FROM workers LIMIT ${clusterCount};";
        $conn->query($procInsert);

        //clustering procedure
        $count = 0;
        while ($count < $repeatTime){
            $count++;
            $procUpdateData = "UPDATE kdata d SET cluster_id = (SELECT id FROM kclusters kc ORDER BY POW(d.first_dim - kc.first_dim,2) + POW(d.second_dim - kc.second_dim,2) ASC LIMIT 1);";
            $conn->query($procUpdateData);
            $procUpdateCluster = "UPDATE kclusters kc, (select cluster_id,
			AVG(first_dim) as first_dim, AVG(second_dim) as second_dim 
			FROM kdata GROUP BY cluster_id) d
		SET kc.first_dim = d.first_dim, kc.second_dim = d.second_dim WHERE kc.id = d.cluster_id;";
            $conn->query($procUpdateCluster);

        }
        return 'Clustering was successful';

    }


    /**
     * @Route("/new", name="kclusters_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $kcluster = new Kclusters();
        $form = $this->createForm(KclustersType::class, $kcluster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($kcluster);
            $entityManager->flush();

            return $this->redirectToRoute('kclusters_index');
        }

        return $this->render('kclusters/new.html.twig', [
            'kcluster' => $kcluster,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="kclusters_show", methods={"GET"})
     */
    public function show(Kclusters $kcluster): Response
    {
        return $this->render('kclusters/show.html.twig', [
            'kcluster' => $kcluster,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="kclusters_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Kclusters $kcluster): Response
    {
        $form = $this->createForm(KclustersType::class, $kcluster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('kclusters_index');
        }

        return $this->render('kclusters/edit.html.twig', [
            'kcluster' => $kcluster,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="kclusters_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Kclusters $kcluster): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kcluster->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($kcluster);
            $entityManager->flush();
        }

        return $this->redirectToRoute('kclusters_index');
    }
}
