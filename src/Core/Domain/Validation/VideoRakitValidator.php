<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;
use Rakit\Validation\Validator;

class VideoRakitValidator implements ValidationInterface
{

    public function validate(Entity $entity): void
    {
        $data = $this->convertEntityForArray($entity);

        $validation = (new Validator())->validate($data, [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'yearLaunched' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        if ($validation->fails()) {
            foreach ($validation->errors()->all() as $error) {
                $entity->notification->addErrors([
                    'context' => 'video',
                    'message' => $error[0]
                ]);
            }
        }

    }

    private function convertEntityForArray(Entity $entity): array
    {
        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'yearLaunched' => $entity->yearLaunched,
            'duration' => $entity->duration
        ];

    }
}
