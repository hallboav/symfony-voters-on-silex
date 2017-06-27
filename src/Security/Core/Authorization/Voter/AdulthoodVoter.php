<?php
namespace App\Security\Core\Authorization\Voter;

use App\Security\Core\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AdulthoodVoter implements VoterInterface
{
    const ADULTHOOD = 'adulthood';

    private function supports($attribute, $subject)
    {
        return self::ADULTHOOD === $attribute;
    }

    private function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
            case self::ADULTHOOD:
                return $this->isAdult($token->getUser());
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function isAdult(User $user)
    {
        return 18 <= $user->getAge();
    }

    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        $vote = self::ACCESS_ABSTAIN;
        foreach ($attributes as $attribute) {
            if (!$this->supports($attribute, $subject)) {
                continue;
            }

            $vote = self::ACCESS_DENIED;
            if ($this->voteOnAttribute($attribute, $subject, $token)) {
                return self::ACCESS_GRANTED;
            }
        }

        return $vote;
    }
}
