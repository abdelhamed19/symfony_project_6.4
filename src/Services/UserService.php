<?php

namespace App\Services;

use App\Kernel;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private Kernel $kernel
    ) {}

    public function getUserByEmail(string $email)
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
