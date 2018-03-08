<?php

namespace CF\MarsubookBundle\Controller;

use CF\MarsubookBundle\Entity\Marsu;
use CF\MarsubookBundle\Form\MarsuEditType;
use CF\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CF\MarsubookBundle\Form\MarsuType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class MarsuController extends Controller
{
  public function homeAction($page)
  {
    if ($page<1) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    $listMarsu = $this
    ->getDoctrine()
    ->getManager()
    ->getRepository('CFMarsubookBundle:Marsu')
    ->findAll()
    ;


    return $this->render('CFMarsubookBundle:Marsu:home.html.twig', array(
      'listMarsu' => $listMarsu,
      'page'      => $page,
    ));
  }

  public function profilAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $marsu = $em->getRepository('CFMarsubookBundle:Marsu')->find($id);

    $user = $this->get('security.token_storage')->getToken()->getUser();
    $friends = $user->getMyFriends();


    if (null === $marsu) {
      throw new NotFoundHttpException("Le marsu d'id ".$id." n'existe pas.");
    }

    return $this->render('CFMarsubookBundle:Marsu:profil.html.twig', array(
      'marsu'           => $marsu,
      'friends'        => $friends,
    ));


  }

  public function inscriptionAction(Request $request)
  {
    $marsu = new Marsu();
    $form = $this->get('form.factory')->create(MarsuType::class, $marsu);


    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($marsu);
      $em->flush();


        $request->getSession()->getFlashBag()->add('notice', 'Bienvenu au nouveau Marsu!');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('cf_marsubook_profil', array('id' => $marsu->getId()));
      }

    return $this->render('CFMarsubookBundle:Marsu:inscription.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function deleteAction(Request $request, $id1, $id2)
  {
    $em = $this->getDoctrine()->getManager();
    $user1 = $em->getRepository('CFUserBundle:User')->find($id1);
    $user2 = $em->getRepository('CFUserBundle:User')->find($id2);
    $marsu = $em->getRepository('CFMarsubookBundle:Marsu')->find($id1);

    $user1->removeMyFriend($user2);
    $em->persist($user1);
    $em->flush();

    $user = $this->get('security.token_storage')->getToken()->getUser();
    $friends = $user->getMyFriends();


    return $this->redirectToRoute('cf_marsubook_home'
    );
  }

  public function editAction($id, Request $request)
  {

    $em = $this->getDoctrine()->getManager();
    $marsu = $em->getRepository('CFMarsubookBundle:Marsu')->find($id);

    if (null === $marsu) {
      throw new NotFoundHttpException("Le marsu d'id ".$id." n'existe pas.");
    }

    $form = $this->get('form.factory')->create(MarsuEditType::class, $marsu);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Informations bien modifiées.');
      return $this->redirectToRoute('cf_marsubook_profil', array('id' => $marsu->getId()));
    }
    return $this->render('CFMarsubookBundle:Marsu:edit.html.twig', array(
      'marsu' => $marsu,
      'form'  => $form->createView(),
    ));
  }

  public function addAction(Request $request, $id1, $id2)
  {
    $em = $this->getDoctrine()->getManager();
    $user1 = $em->getRepository('CFUserBundle:User')->find($id1);
    $user2 = $em->getRepository('CFUserBundle:User')->find($id2);
    $marsu = $em->getRepository('CFMarsubookBundle:Marsu')->find($id1);

    $user1->addMyFriend($user2);
    $em->persist($user1);
    $em->flush();

    $user = $this->get('security.token_storage')->getToken()->getUser();
    $friends = $user->getMyFriends();


    return $this->render('CFMarsubookBundle:Marsu:profil.html.twig', array(
      'marsu'           => $marsu,
      'friends'        => $friends,
    ));


  }
}
