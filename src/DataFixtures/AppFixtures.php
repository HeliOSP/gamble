<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        $nameList = [
            "Slender Blind Snakes",
            "Kitti's Hog-Nosed Bat",
            "Bee Hummingbird",
            "Speckled Padloper Tortoise",
            "Etruscan Shrew",
            "Madame Berthe's Mouse Lemur",
            "Pygmy Marmoset",
            "Pygmy Rabbit"
        ];

        foreach ($nameList as $name) {
            $user = new User();
            $user->setName($this->addColorToName($name));

            $manager->persist($user);
        }
    }

    private function addColorToName($name)
    {
        $colorList = [
            "White", "Silver", "Gray", "Black", "Red", "Maroon", "Yellow", "Olive",
            "Lime", "Green", "Aqua", "Teal", "Blue", "Navy", "Fuchsia", "Purple"
        ];
        $color = $colorList[rand(0, count($colorList) - 1)];

        return "$color $name";
    }
}
