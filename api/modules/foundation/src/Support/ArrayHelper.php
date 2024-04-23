<?php

declare(strict_types=1);

namespace Juling\Foundation\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

trait ArrayHelper
{
    public function collect(): Collection
    {
        return collect($this->toArray(true));
    }

    public function toArray($allProperty = false): array
    {
        return $allProperty ? $this->allProperty() : $this->effectiveProperty();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function setData(array $row): void
    {
        foreach ($row as $col => $val) {
            if (! is_null($val)) {
                $setMethod = 'set'.Str::studly($col);
                if (method_exists($this, $setMethod)) {
                    $this->$setMethod($val);
                }
            }
        }
    }

    /**
     * 返回对象的全部属性
     */
    protected function getProperties(): array
    {
        $reflect = new ReflectionClass(__CLASS__);

        return $reflect->getProperties(ReflectionProperty::IS_PRIVATE);
    }

    /**
     * 对象的全部属性赋值
     */
    private function allProperty(): array
    {
        $props = $this->getProperties();

        $data = [];
        foreach ($props as $prop) {
            $name = $prop->getName();
            $data[$name] = $this->$name ?? null;
        }

        return $data;
    }

    /**
     * 返回赋值的对象属性
     */
    private function effectiveProperty(): array
    {
        $data = [];
        foreach ($this as $k => $v) {
            $data[$k] = $v;
        }

        return $data;
    }
}
