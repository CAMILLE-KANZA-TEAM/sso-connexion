<?php

namespace App\Controller;

use App\Services\SsoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallbackController extends AbstractController
{




    /**
     * @Route("/callback", name="app_callback")
     */
    public function index(SsoManager $ssoManager): Response
    {
        return $this->render('callback/index.html.twig', [
            'controller_name' => 'CallbackController',
        ]);
    }




}
