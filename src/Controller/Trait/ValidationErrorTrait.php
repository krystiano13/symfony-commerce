<?php

namespace App\Controller\Trait;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

trait ValidationErrorTrait
{
    private function handleErrors(Request &$request ,&$errors, array &$messages)
    {
        if(count($errors) > 0 || count($messages) > 0)
        {
            if(count($errors) > 0) {
                foreach ($errors as $violation) {
                    $msg = "{$violation->getPropertyPath()} - {$violation->getMessage()}";
                    array_unshift($messages,$msg);
                }
            }
        }

        if(count($messages) > 0) {
            $prevRoute = $request->headers->get('referer');
            return $this->redirectToRoute(
                $prevRoute,
                ["errors" => $messages],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}