<?php

namespace Juling\Filesystem\Obs\Internal\Common;

interface ITransform {
    public function transform($sign, $para);
}

