<?php

namespace App\Foundation\Filesystem\Obs\Internal\Signature;

use App\Foundation\Filesystem\Obs\Internal\Common\Model;

interface SignatureInterface
{
	function doAuth(array &$requestConfig, array &$params, Model $model);
}
