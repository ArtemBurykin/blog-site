<?php
namespace App\Tests\Traits;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @method static getContainer()
 */
trait DependenciesTrait
{
    protected function getUserRepository(): UserRepository
    {
        return static::getContainer()->get(UserRepository::class);
    }

    protected function getPasswordHasher(): UserPasswordHasherInterface
    {
        return static::getContainer()->get(UserPasswordHasherInterface::class);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get(EntityManagerInterface::class);
    }

}
