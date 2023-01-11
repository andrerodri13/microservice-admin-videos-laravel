<?php

namespace Core\Domain\Factory;

use Core\Domain\Validation\ValidationInterface;
use Core\Domain\Validation\VideoLaravelValidator;
use Core\Domain\Validation\VideoRakitValidator;

class VideoValidatorFactory
{

    public static function create(): ValidationInterface
    {
//        return new VideoLaravelValidator();
        return new VideoRakitValidator();
    }
}
