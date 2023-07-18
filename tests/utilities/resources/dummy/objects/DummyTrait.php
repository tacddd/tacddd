<?php
/**
 *   _____          ____  ____  ____
 *  |_   ___ _  ___|  _ \|  _ \|  _ \
 *    | |/ _` |/ __| | | | | | | | | |
 *    | | (_| | (__| |_| | |_| | |_| |
 *    |_|\__,_|\___|____/|____/|____/
 *
 * @category    TacDDD
 * @package     TacDDD
 * @author      wakaba <wakabadou@gmail.com>
 * @copyright   Copyright (c) @2023  Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/). All rights reserved.
 * @license     http://opensource.org/licenses/MIT The MIT License.
 *              This software is released under the MIT License.
 * @varsion     1.0.0
 */

declare(strict_types=1);

namespace tacddd\tests\utilities\resources\dummy\objects;

trait DummyTrait
{
    public static string $publicStatic  = 'publicStatic';

    protected static string $protectedStatic  = 'protectedStatic';

    private static string $privateStatic  = 'privateStatic';

    public string $public  = 'public';

    protected string $protected  = 'protected';

    private string $private  = 'private';

    public static function publicStatic(): string
    {
        return self::$publicStatic;
    }

    protected static function protectedStatic(): string
    {
        return self::$protectedStatic;
    }

    private static function privateStatic(): string
    {
        return self::$privateStatic;
    }

    public function __construct()
    {
    }

    public function public(): string
    {
        return $this->public;
    }

    protected function protected(): string
    {
        return $this->protected;
    }

    private function private(): string
    {
        return $this->private;
    }
}
