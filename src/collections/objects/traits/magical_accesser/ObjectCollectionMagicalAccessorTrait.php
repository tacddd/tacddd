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

namespace tacddd\collections\objects\traits\magical_accesser;

/**
 * オブジェクトコレクションマジックアクセス特性
 */
trait ObjectCollectionMagicalAccessorTrait
{
    /**
     * マップキーをパースして返します。
     *
     * @param  string $find_key  マップキー
     * @param  string $separator セパレータ
     * @return array  キー配列
     */
    public static function parseFindKey(string $find_key, string $separator): array
    {
        if (!\str_contains($find_key, $separator)) {
            return [$find_key];
        }

        return \explode($separator, $find_key);
    }

    /**
     * Magical method
     *
     * @param  string $method_name メソッド名
     * @param  array  $arguments   引数
     * @return mixed  返り値
     */
    public function __call(string $method_name, array $arguments): mixed
    {
        foreach (static::ACTION_SPEC_MAP as $action => $spec) {
            if (\str_starts_with($method_name, $action)) {
                $find_key   = \mb_substr($method_name, $spec['length']);
                $action     = \mb_substr($action, 0, $spec['length']);
                $use_args   = $spec['use_args'];
                $separator  = $spec['separator'];

                $criteria   = [];

                $find_keys  = $this->parseFindKey($find_key, $separator);

                if ($use_args) {
                    foreach ($find_keys as $idx => $cache_key) {
                        if (!\array_key_exists($idx, $arguments)) {
                            $debug_backtrace = \debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

                            throw new \ArgumentCountError(\sprintf('Too few arguments to function %s::%s(), %d passed in %s on line %s and exactly %d expected', static::class, $method_name, \count($arguments), $debug_backtrace[0]['file'], $debug_backtrace[0]['line'], \count($find_keys)));
                        }

                        $criteria[\ltrim(\strtolower(\preg_replace('/[A-Z]/u', '_\0', $cache_key)), '_')]   = $arguments[$idx];
                    }

                    $parameters = [
                        $criteria,
                    ];

                    for (++$idx,$argument_length = \count($arguments);$idx < $argument_length;++$idx) {
                        $parameters[]   = $arguments[$idx];
                    }
                } else {
                    $parameters     = [$find_keys];
                }

                return match ($action) {
                    'findOneToMapBy'    => $this->findOneToMapBy(...$parameters),
                    'findToMapBy'       => $this->findToMapBy(...$parameters),
                    'toOneMapIn'        => $this->toOneMap(...$parameters),
                    'findOneBy'         => $this->findOneBy(...$parameters),
                    'removeBy'          => $this->removeBy(...$parameters),
                    'toMapIn'           => $this->toMap(...$parameters),
                    'findBy'            => $this->findBy(...$parameters),
                    'hasBy'             => $this->hasBy(...$parameters),
                    default             => throw new \Error(\sprintf('Call to undefined method %s::%s()', static::class, $method_name)),
                };
            }
        }

        throw throw new \Error(\sprintf('Call to undefined method %s::%s()', static::class, $method_name));
    }
}
