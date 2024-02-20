<?php

declare(strict_types=1);

namespace App\Support;

use ReflectionEnum;
use ReflectionException;

class AnnotationHelper
{
    /**
     * @throws ReflectionException
     */
    public function getReflectionEnums($objectOrClass): array
    {
        $list = [];

        $reflectionEnum = new ReflectionEnum($objectOrClass);
        foreach ($reflectionEnum->getCases() as $case) {
            $docComment = $case->getDocComment();
            preg_match('/\/\*\*\n.+\*(.+)\n/', $docComment, $matches);
            $list[] = [
                'name' => $matches[1] ? trim($matches[1]) : '',
                'val' => $case->getValue(),
            ];
        }

        return $list;
    }
}
