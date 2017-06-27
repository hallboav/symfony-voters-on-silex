<?php
namespace App\Application;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait ControllerTrait
{
    public function denyAccessUnlessGranted($attributes, $subject = null, $message = 'Access Denied.')
    {
        if (!$this['security.authorization_checker']->isGranted($attributes, $subject)) {
            throw $this->createAccessDeniedException($message);
        }
    }

    private function createAccessDeniedException($message = null)
    {
        return new AccessDeniedHttpException($message);
    }
}
