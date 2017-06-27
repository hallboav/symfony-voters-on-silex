<?php
namespace App\Security\Core\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class InMemoryUserProvider implements UserProviderInterface
{
    private $users;

    public function __construct(array $users = [])
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    public function addUser(UserInterface $user)
    {
        if (isset($this->users[strtolower($user->getUsername())])) {
            throw new \LogicException('Another User with the same name already exists.');
        }

        $this->users[strtolower($user->getUsername())] = $user;
    }

    private function getUser($username)
    {
        if (!isset($this->users[strtolower($username)])) {
            $exception = new UsernameNotFoundException(sprintf('User "%s" does not exist.', $username));
            $exception->setUsername($username);

            throw $exception;
        }

        return $this->users[strtolower($username)];
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->getUser($username);
        return new User($user->getUsername(), $user->getPassword(), $user->getAge(), $user->getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user = $this->getUser($user->getUsername());
        return new User($user->getUsername(), $user->getPassword(), $user->getAge(), $user->getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
