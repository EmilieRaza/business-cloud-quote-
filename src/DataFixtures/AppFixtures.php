<?php

namespace App\DataFixtures;

use App\Entity\File;
use App\Entity\User;
use App\Entity\UserAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 500; $i++) {
            $shortDescription = $this->faker->realText(512);
            $description = '';
            for ($j = 0; $j < 5; $j++) {
                $description .= '<p>';
                $description .= $this->faker->realText(1024);
                $description .= '</p>';
            }

            $user = (new User())
                ->setEmail("test$i@faker.com")
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName)
                ->setPhone1($this->faker->phoneNumber)
                ->setEnabled(true)
                ->setShortDescription($shortDescription)
                ->setDescription($description)
                ->setPhoto($this->createPhoto())
                ->setCroppedPhoto($this->getCroppedPhoto())
                ->setCroppedPhotoThumbnail($this->getCroppedPhotoThumbnail())
                ->setAddress($this->createAddress());
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    "password$i"
                )
            );
            $user->setRoles(['ROLE_AGENT']);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createAddress()
    {
        $address = new UserAddress();
        $address->setStreet($this->faker->streetAddress);
        $address->setCity($this->faker->city);
        $address->setZipCode($this->faker->biasedNumberBetween(75000, 75020));
        $other = [];
        for ($i = 0; $i < 5; $i++) {
            $other[] = $this->faker->biasedNumberBetween(75000, 75020);
        }
        $address->setOther(implode(';', $other));
        return $address;
    }

    private function createPhoto()
    {
        return (new File())
            ->setName('file')
            ->setUpdatedAt(new \DateTime())
            ->setExtension('ext');
    }

    private function getCroppedPhoto()
    {
        return '';
    }

    private function getCroppedPhotoThumbnail()
    {
        return '';
    }
}
