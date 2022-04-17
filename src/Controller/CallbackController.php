<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Services\SsoManager;
use Doctrine\ORM\EntityManagerInterface;
use Hybridauth\Hybridauth;
use Hybridauth\Storage\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class CallbackController extends AbstractController
{




    /**
     * @Route("/callback", name="app_callback")
     */
    public function index(SsoManager $ssoManager, EntityManagerInterface $entityManager, UserRepository $userRepository, LoginAuthenticator $authenticator): Response
    {

        /**
         * Feed configuration array to Hybridauth.
         */
        $hybridauth = new Hybridauth($ssoManager->getConfig());

        /**
         * Initialize session storage.
         */
        $storage = new Session();

        /**
         * Hold information about provider when user clicks on Sign In.
         */
        if (isset($_GET['provider'])) {
            $storage->set('provider', $_GET['provider']);
        }

        /**
         * When provider exists in the storage, try to authenticate user and clear storage.
         *
         * When invoked, `authenticate()` will redirect users to provider login page where they
         * will be asked to grant access to your application. If they do, provider will redirect
         * the users back to Authorization callback URL (i.e., this script).
         */

        if ($provider = $storage->get('provider')) {
            $hybridauth->authenticate($provider);
        }


        $adapter = $hybridauth->getAdapter($storage->get('provider'));
        if ($adapter->getUserProfile()) {

            // on recupère l'adresse mail
            $email = $adapter->getUserProfile()->email;

            // si l'utilisateur n'existe déjà, on l'inscript
            $currentUser = $userRepository->findOneBy(['email' => $email]);
            if (empty($currentUser)) {
                $password = md5(time());
                $user = new User();
                $user->setEmail($email)->setPassword($password);
                $entityManager->persist($user);
                $entityManager->flush();
            }

            // autologin
            $providerKey = 'app_user_provider'; // your firewall name
            $token = new UsernamePasswordToken($currentUser, null, $providerKey, $currentUser->getRoles());
            $this->container->get('security.token_storage')->setToken($token);


        }
        return $this->redirectToRoute('app_index');
    }




}
