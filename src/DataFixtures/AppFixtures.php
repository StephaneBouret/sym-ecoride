<?php

namespace App\DataFixtures;

use App\Entity\TravelPreference;
use App\Entity\User;
use App\Enum\DiscussionPreference;
use App\Enum\MusicPreference;
use App\Enum\PetPreference;
use App\Enum\SmokingPreference;
use App\Service\AvatarService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(protected UserPasswordHasherInterface $passwordHasher, protected AvatarService $avatarService) {}

    public static function getGroups(): array
    {
        return ['app'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $phoneNumberUtil = PhoneNumberUtil::getInstance(); // Instance de PhoneNumberUtil

        $admin = new User();
        $hash = $this->passwordHasher->hashPassword($admin, "password");

        // Génère un numéro de téléphone pour l'admin
        $adminRawPhoneNumber = $faker->mobileNumber();
        $adminPhoneNumberObject = $phoneNumberUtil->parse($adminRawPhoneNumber, 'FR');

        $admin->setFirstname("Admin")
            ->setLastname("Admin")
            ->setRoles(['ROLE_ADMIN'])
            ->setAdress($faker->streetAddress())
            ->setPostalCode($faker->postcode())
            ->setCity($faker->city())
            ->setPseudo('admin')
            // ->setPhone($faker->phoneNumber())
            ->setPhone($adminPhoneNumberObject)
            ->setCredit(100)
            ->setEmail("admin@gmail.com")
            ->setPassword($hash);

        $manager->persist($admin);
        $this->avatarService->createAndAssignAvatar($admin);

        // TravelPreference pour admin
        $adminPref = new TravelPreference();
        $adminPref->setUser($admin)
            ->setDiscussion(DiscussionPreference::default())
            ->setMusic(MusicPreference::default())
            ->setSmoking(SmokingPreference::default())
            ->setPets(PetPreference::default());
        $admin->setTravelPreference($adminPref);
        $manager->persist($adminPref);

        $users = [];
        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->passwordHasher->hashPassword($user, "password");

            // Génère un numéro de téléphone différent pour chaque utilisateur
            $rawPhoneNumber = $faker->mobileNumber();
            $phoneNumberObject = $phoneNumberUtil->parse($rawPhoneNumber, 'FR');

            $user->setEmail("user$u@gmail.com")
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setAdress($faker->streetAddress())
                ->setPostalCode($faker->postcode())
                ->setCity($faker->city())
                ->setPseudo($faker->unique()->userName() . $u)
                // ->setPhone($faker->phoneNumber())
                ->setPhone($phoneNumberObject)
                ->setCredit(20)
                ->setPassword($hash);

            $manager->persist($user);
            $this->avatarService->createAndAssignAvatar($user);

            // Nouvelle entité TravelPreference
            $travelPreference = new TravelPreference();
            $travelPreference->setUser($user)
                ->setDiscussion(DiscussionPreference::default())
                ->setMusic(MusicPreference::default())
                ->setSmoking(SmokingPreference::default())
                ->setPets(PetPreference::default());
            $user->setTravelPreference($travelPreference);
            $manager->persist($travelPreference);

            $users[] = $user;
        }

        $manager->flush();
    }
}
