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

/**
 * 指定された変数をダンプし終了します。
 *
 * @param mixed ...$args dump対象の引数
 */
function dd(...$args): void
{
    ['file' => $file, 'line' => $line, 'args' => $args] = \debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0];

    $target_line = $line - 2;

    foreach (new \SplFileObject($file) as $idx => $row) {
        if ($idx > $target_line) {
            break;
        }
    }

    // key割り出し
    $caller = \sprintf('%s(%s): %s', $file, $line, $row);

    $debug = function($arg): Generator {
        yield match (\gettype($arg)) {
            'boolean'   => $arg ? 'true' : 'false',
            'integer'   => (string) $arg,
            'double'    => false === \mb_strpos((string) $arg, '.') ? \sprintf('%s.0', $arg) : (string) $arg,
            'string'    => $arg,
            'array'     => \print_r($arg, true),
            'object'    => \print_r($arg, true),
            'resource'  => \sprintf('resource #%s', $arg),
            'NULL'      => 'null',
        };
    };

    echo $caller, \PHP_EOL;

    foreach ($args as $arg) {
        foreach ($debug($arg) as $output) {
            echo $output, \PHP_EOL;
        }
    }

    exit;
}
