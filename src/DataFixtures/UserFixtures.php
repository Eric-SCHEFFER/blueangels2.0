<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User;
        $user
        ->setEmail('ti@ti.com')
        ->setRoles(['ROLE_ADMIN'])
        ->setPassword($this->encoder->encodePassword($user, 'azerty'));
        $manager->persist($user);
        $manager->flush();
    }
}
