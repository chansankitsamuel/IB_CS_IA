<?php

declare(strict_types=1);

/**
 * This file is part of Shield OAuth.
 *
 * (c) Datamweb <pooya_parsa_dadashi@yahoo.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Datamweb\ShieldOAuth\Config;

use Datamweb\ShieldOAuth\Views\Decorators\ShieldOAuth;

class Registrar
{
    /**
     * Register the ShieldOAuth decorator.
     */
    public static function View(): array
    {
        return [
            'decorators' => [
                ShieldOAuth::class,
            ],
        ];
    }
}
