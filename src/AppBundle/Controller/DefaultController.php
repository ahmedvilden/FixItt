<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use UserBundle\Entity\User;

class DefaultController extends Controller
{



    public function getParent(){
        return 'FOSUserBundle';
    }

    /**
     * @Route("/login", name="homepage")
     */
    public function loggedAction()
    {

                return $this->render('@User/Dashboard/Test.html.twig');


    }

    /**
     * @Route("/register/", name="homepage")
     */
    public function loggedRAction()
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->render('@User/Dashboard/Test.html.twig');
        }elseif ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            return $this->render('@User/Dashboard/indexAdmin.html.twig');
        }
    }

    /**
     * @Route("user/login", name="homepage")
     */
    public function redirectAction()
    {


            if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
                return $this->redirectToRoute('indexAdmin');
            } else {
                return $this->redirectToRoute('album');            }


        }

}
