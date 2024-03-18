<?php 

namespace App\Service;

use App\Exception\InvalidArgument;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate($entity)
    {
        $errors = $this->validator->validate($entity);

        if (! empty($errors)) {
            foreach ($errors as $message) {
                $messages['errors'][] = [
                    'property' => $message->getPropertyPath(),
                    'value' => $message->getInvalidValue(),
                    'message' => $message->getMessage(),
                ];
            }
        }

        if (! empty($messages['errors']) > 0) {
            throw new InvalidArgument( $messages['errors']);
        }
    }
}