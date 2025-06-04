<?php

namespace App\Service;

use App\Entity\Avatar;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AvatarService
{
    public function __construct(protected EntityManagerInterface $em, protected ParameterBagInterface $params)
    {}

    public function createAndAssignAvatar(User $user): Avatar
    {
        $avatar = new Avatar();
        $initial = strtoupper(substr($user->getFirstname(), 0, 1));
        $avatarsDirectory = $this->params->get('avatars_directory');
        $outputPath = $avatarsDirectory . '/' . uniqid() . '.png';
        $avatar->createDefaultAvatar($initial, $outputPath);
        $avatar->setImageName(basename($outputPath));
        $avatar->setUser($user);
        $avatar->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($avatar);
        $this->em->flush();
        return $avatar;
    }
}