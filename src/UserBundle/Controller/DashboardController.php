<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;
use FOS\UserBundle\Controller\SecurityController;

class DashboardController extends SecurityController
{


    /*  public function loginAdminAction()
      //
      //{
          if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
              return $this->render('@User/Dashboard/indexAdmin.html.twig');
          }else
              return $this->render('@User/Dashboard/loginAdmin.html.twig');
      }*/
    public function indexAdminAction()
    {
        return $this->render('@User/Dashboard/indexAdmin.html.twig');
    }

    public function testAction()
    {
        return $this->render('@User/Dashboard/test.html.twig');
    }

    public function loginAdminAction()
    {
        return $this->render("@FOSUser/Security/loginAdmin.html.twig");

    }
}