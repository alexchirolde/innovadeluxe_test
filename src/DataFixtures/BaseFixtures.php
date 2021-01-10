<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class BaseFixtures
 * @package App\DataFixtures
 */
abstract class BaseFixtures extends Fixture
{
    /** @var Generator */
    protected $faker;
    /**
     * @var ObjectManager
     */
    private $manager;

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }
}