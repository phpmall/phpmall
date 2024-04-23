<?php

declare(strict_types=1);

namespace Juling\Foundation\Support;

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

    /**
     * @throws ReflectionException
     */
    public function getElementByName($objectOrClass, string $name): array
    {
        $enums = $this->getReflectionEnums($objectOrClass);

        $result = [];
        foreach ($enums as $enum) {
            if ($enum['val']->name === $name) {
                $result = [
                    'name' => $enum['name'],
                    'code' => $enum['val']->name,
                    'value' => $enum['val']->value,
                ];
            }
        }

        return $result;
    }

    /**
     * @throws ReflectionException
     */
    public function getElementByVal($objectOrClass, $val): array
    {
        $enums = $this->getReflectionEnums($objectOrClass);

        $result = [];
        foreach ($enums as $enum) {
            if ($enum['val']->value === $val) {
                $result = [
                    'name' => $enum['name'],
                    'code' => $enum['val']->name,
                    'value' => $enum['val']->value,
                ];
            }
        }

        return $result;
    }
}
