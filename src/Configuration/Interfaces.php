<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use Cornfield\Core\Session\NativeSession;
use Cornfield\Core\Session\SessionInterface;

return [
    SessionInterface::class => static function (): SessionInterface {
        return new NativeSession();
    },
];
