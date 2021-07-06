<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/series", name="serie")
 */

class SerieController extends AbstractController
{
    /**
     * @Route("", name="List")
     */
    public function List(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findBestSeries();


        return $this->render('serie/List.html.twig', [
            "series" => $series

        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */

    public function details(int $id, SerieRepository  $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie){

            throw $this->createNotFoundException('oh no !!!');
        }


        return $this->render('serie/details.html.twig', [
        "serie" => $serie
        ]);
    }

    /**
     * @Route("/create", name="create")
     */

    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serie->setDateCreated(new \DateTime());
        $serieForm = $this->createForm(SerieType::class, $serie);

        //traiter formulaire
        $serieForm->handleRequest($request);

        //traiter le formulaire

        if ($serieForm->isSubmitted() && $serieForm->isValid()){

            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Serie added ! Good job.');
            return $this->redirectToRoute('seriedetails', ['id' => $serie->getId()]);
        }



        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);

    }

    /**
     * @Route ("/demo", name="em-demo")
     */
    public function demo(EntityManagerInterface $entityManager): Response
    {

        //cree une instance de mon entité
        $serie = new Serie();

        //hydrate toutes les series
        $serie->setName('pif');
        $serie->setBackdrop('test de backdrop');
        $serie->setPoster('poster');
        $serie->setDateCreated(new \DateTime());
        $serie->setFirstAirDate(new \DateTime("- 1 year"));
        $serie->setLastAirDate(new \DateTime("- 6 month"));
        $serie->setGenres('drame');
        $serie->setOverview('bblablalbalbalb');
        $serie->setPopularity(123.00);
        $serie->setVote(8.2);
        $serie->setStatus('Canceled');
        $serie->setTmdbId(325651);

        dump($serie);

        //commit d ajouter en bdd
        $entityManager->persist($serie);
        $entityManager->flush();

        //edit
        //$serie->setGenres('comedy');

        //commit de supprimer en bdd
        //dump($serie);
        //$entityManager->remove($serie);
        //$entityManager->flush();


        //$entityManager = $this->getDoctrine()->getManager();



        return $this->render('serie/create.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Serie $serie, EntityManagerInterface $entityManager)
    {


        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }
}
