<?php

namespace Juling\Filesystem\Obs\Internal\Signature;

use Juling\Filesystem\Obs\Internal\Common\Model;

interface SignatureInterface
{
	function doAuth(array &$requestConfig, array &$params, Model $model);
}
