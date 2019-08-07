<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use function DI\env;

return [
    'environment' => env('PHP_ENVIRONMENT', Constants::ENV_PRODUCTION),
];
