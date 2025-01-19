<?php

namespace App\Controller\Trait;

trait ValidationErrorTrait
{
    private function handleErrors(&$errors, array &$messages)
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
    }
}