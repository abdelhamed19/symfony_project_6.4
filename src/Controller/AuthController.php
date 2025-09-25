<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Services\UserService;
use App\Services\RestHelperService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/api/auth')]
final class AuthController extends AbstractFOSRestController
{
    public function __construct(
        private RestHelperService $rest,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $em,
        private JWTTokenManagerInterface $jwtManager,
        private UserService $userService
    ) {}

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request)
    {
        $formUser = new User();
        $form = $this->createForm(LoginType::class, $formUser);
        $form->submit($request->request->all());

        if ($form->isValid() && $form->isSubmitted()) {
            $user = $this->userService->getUserByEmail($request->request->get('email'));
            if (!$user) {
                $this
                    ->rest
                    ->failed()
                    ->addMessage('Invalid credentials')
                    ->setData(null);
                return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
            }
            $isPasswordValid = $this->passwordHasher->isPasswordValid($user, $request->request->get('password'));
            if (!$isPasswordValid) {
                $this
                    ->rest
                    ->failed()
                    ->addMessage('Invalid credentials')
                    ->setData(null);
                return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
            }

            $token = $this->jwtManager->create($user);
            $this
                ->rest
                ->addMessage('Login successful')
                ->setData(['token' => $token, 'user' => $user]);

            return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_OK));
        }

        $this->rest
            ->failed()
            ->setFormErrors($form->getErrors(true))
            ->setData(null);
        return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
    }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request)
    {
        $user = new User();
        $data = $request->request->all();
        $form = $this->createForm(RegisterType::class, $user);
        $form->submit($data);

        if ($form->isValid() && $form->isSubmitted()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $this->em->persist($user);
            $this->em->flush();

            $token = $this->jwtManager->create($user);

            $this
                ->rest
                ->addMessage('Registration successful')
                ->setData(['token' => $token, 'user' => $user]);

            return $this->handleView($this->view($this->rest->getResponse(), 200));
        }

        $this->rest
            ->failed()
            ->setFormErrors($form->getErrors(true))
            ->setData(null);
        return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
    }
}
