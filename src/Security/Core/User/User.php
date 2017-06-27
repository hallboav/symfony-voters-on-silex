<?php
namespace App\Security\Core\User;

use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    private $username;
    private $password;
    private $age;
    private $roles;

    public function __construct($username, $password, $age, array $roles = [])
    {
        if ('' === $username || null === $username) {
            throw new \InvalidArgumentException('The name cannot be empty.');
        }

        if (!is_int($age)) {
            throw new \InvalidArgumentException('Age must be an integer.');
        }

        $this->username = $username;
        $this->password = $password;
        $this->age = $age;
        $this->roles = $roles;
    }

    public function getAge()
    {
        return $this->age;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
