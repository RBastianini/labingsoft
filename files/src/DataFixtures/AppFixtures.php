<?php

namespace App\DataFixtures;

use App\Entity\Forecast;
use App\Entity\Location;
use App\Entity\User;
use App\Enum\ShortWeatherDescription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User('admin@example.com', null);
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, 'tutta maiuscola senza spazi'); // :)
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $rome = new Location('Roma', 'IT');
        $rome->setLatitude('41.90270080');
        $rome->setLongitude('12.49623520');
        $forecast1Rome = new Forecast($rome, new \DateTimeImmutable('today'), ShortWeatherDescription::CLOUDY);
        $forecast2Rome = new Forecast($rome, new \DateTimeImmutable('tomorrow'), ShortWeatherDescription::SUNNY);
        $perugia = new Location('Perugia', 'IT');
        $perugia->setLatitude('43.1122000');
        $perugia->setLongitude('12.3887800');
        $forecast1Perugia = new Forecast($perugia, new \DateTimeImmutable('today'), ShortWeatherDescription::RAINY);
        $forecast2Perugia = new Forecast(
            $perugia,
            new \DateTimeImmutable('tomorrow'),
            ShortWeatherDescription::PARTLY_CLOUDY
        );
        $manager->persist($rome);
        $manager->persist($forecast1Rome);
        $manager->persist($forecast2Rome);
        $manager->persist($perugia);
        $manager->persist($forecast1Perugia);
        $manager->persist($forecast2Perugia);
        $manager->persist(new Location('Foligno', 'IT'));
        $manager->persist(new Location('Terni', 'IT'));
        $manager->persist(new Location('Orvieto', 'IT'));
        $manager->persist(new Location('Assisi', 'IT'));
        $manager->persist(new Location('Norcia', 'IT'));
        $manager->persist(new Location('Gualdo-Tadino', 'IT'));
        $manager->persist(new Location('Gubbio', 'IT'));
        $manager->flush();
    }
}
