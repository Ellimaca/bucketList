<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{

    /**
     * @Route("/home", name="main_home")
     */
    public function home() {
        return $this->render('main/home.html.twig', );
    }

    /**
     * @Route("/test", name="main_test")
     */
    public function test() {

        return $this->render('main/test.html.twig');
    }

    /**
     * @Route("/contact", name="main_contact")
     */
    public function contact() {

        return $this->render('main/contact.html.twig');
    }

    /**
     * @Route("/reservation", name="main_reservation")
     */
    public function reservation() {

        return $this->render('main/reservation.html.twig');
    }

}