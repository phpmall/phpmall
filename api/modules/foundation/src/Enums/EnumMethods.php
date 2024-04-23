<?php

declare(strict_types=1);

namespace Juling\Foundation\Enums;

use Juling\Foundation\Support\AnnotationHelper;
use Illuminate\Support\Facades\Log;
use Throwable;

trait EnumMethods
{
    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getDescription(): string
    {
        static $annotationHelper = null;
        if (is_null($annotationHelper)) {
            $annotationHelper = new AnnotationHelper();
        }

        try {
            $elementInfo = $annotationHelper->getElementByName($this, $this->name);
            if (empty($elementInfo)) {
                return $this->name;
            }

            return $elementInfo['name'];
        } catch (Throwable $e) {
            Log::error($e);

            return '';
        }
    }
}
