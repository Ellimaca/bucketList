<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Reaction;
use App\Entity\Wish;
use App\Form\ReactionType;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/list", name="wish_list")
     */
    public function list(WishRepository $rep): Response
    {
        $wishes = $rep->findWishList();
        $categories = $rep->findAll();

        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes,
            "categories" => $categories
        ]);
    }


    /**
     * @Route("list/detail{id}", name="wish_detail")
     */
    public function detail($id, WishRepository $rep, Request $request, EntityManagerInterface $manager): Response
    {
        $wish = $rep->find($id);
        $reactions = $rep->findAll();

        $reaction = new Reaction();

        $reactionForm = $this->createForm(ReactionType::class, $reaction);

        $reactionForm->handleRequest($request);

        if($reactionForm->isSubmitted() && $reactionForm->isValid()) {
            $reaction->setDateCreated(new \DateTime());
            $reaction->getWish()->addReaction($reaction);

            $manager->persist($reaction);
            $manager->flush();

            $this->addFlash('success', "Your comment has been added");
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/detail.html.twig', [
            "wish" => $wish,
            "reactions" => $reactions,
            "reaction" => $reaction,
            "reactionForm" => $reactionForm->createView()
        ]);


    }

    /**
     * @Route("/add-your-wish", name="wish_your_wish")
     */
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        //on instancie l'entité associée au formulaire dans le controleur

        //crée un wish vide pour que Symfony puisse y injecter les données
        $wish = new Wish();

        //je crée mon formulaire
        $wishForm = $this->createForm(WishType::class, $wish);

        //récupère les données soumises s'il y a lieu
        $wishForm->handleRequest($request);

        //si le formulaire est soumis et valide...
        if($wishForm->isSubmitted() && $wishForm->isValid()) {

            //hydrater les propriétés manquantes
            $wish->setLikes(0);
            $wish->setDateCreated(new \DateTime());
            $wish->setIsPublished(true);


            //sauvegarde en bdd
            $manager->persist($wish);
            $manager->flush();

            //affiche un message sur la prochaine page
            $this->addFlash('success', 'Wish added');
            //et on redirige vers la page de détail avec son wish
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);

            //sinon, si le formulaire est soumis mais pas valide on affiche un message d'erreur
        } elseif ($wishForm->isSubmitted() && !$wishForm->isValid()) {
            $this->addFlash('danger', "We can't create your wish, please check the errors lower");
        }


        return $this->render('wish/add.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }

}
