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
 * @version     1.0.0
 */

declare(strict_types=1);

namespace tacddd;

/**
 * @var int におけるデフォルトインデントレベル
 */
const TO_DEBUG_STRING_DEFAULT_INDENT_LEVEL  = 0;

/**
 * @var int におけるデフォルトインデント幅
 */
const TO_DEBUG_STRING_DEFAULT_INDENT_WIDTH  = 4;

/**
 * 指定された変数をダンプし終了します。
 *
 * @param mixed ...$args dump対象の引数
 */
function dd(...$args): void
{
    \tacddd\d(...$args);

    exit;
}

/**
 * 指定されたファイルの指定された行にある関数の引数定義表記を返します。
 *
 * @param  string|\SplFileInfo $file      ファイル
 * @param  int                 $base_line 行
 * @return array               引数定義
 */
function get_args_definition_text(
    string|\SplFileInfo $file,
    int $base_line,
): array {
    $tokens     = \PhpToken::tokenize(\file_get_contents($file instanceof \SplFileInfo ? $file->getPathname() : $file));

    $in_work    = false;

    $parenthesis_stack        = 0;
    $passed_first_parenthesis = false;

    $caller_stack   = [];
    $var_names      = [];
    $sub_var_names  = [];

    foreach ($tokens as $token) {
        if ($token->line === $base_line) {
            $in_work        = true;
        }

        if (!$in_work) {
            continue;
        }

        if ($token->is(\T_COMMENT)) {
            $caller_stack[] = $token->text;
            continue;
        }

        if (!$passed_first_parenthesis) {
            if ($token->text === '(') {
                $passed_first_parenthesis   = true;
                ++$parenthesis_stack;
            }
            $caller_stack[] = $token->text;
            continue;
        }

        if ($token->text === '(') {
            $sub_var_names  = [];
            ++$parenthesis_stack;
        } elseif ($token->text === ')') {
            --$parenthesis_stack;
            $caller_stack[] = $token->text;

            if ($parenthesis_stack === 1) {
                $last_key               = \array_key_last($var_names);

                if ($last_key === null) {
                    continue;
                }

                $var_names[$last_key]   = \sprintf('%s%s)', $var_names[$last_key], \implode('', $sub_var_names));
                $sub_var_names          = [];

                continue;
            }

            if ($parenthesis_stack === 0) {
                break;
            }
        }

        if ($parenthesis_stack > 1) {
            $sub_var_names[]    = $token->text;
            $caller_stack[]     = $token->text;
            continue;
        }

        if ($token->is(\T_WHITESPACE)) {
            $caller_stack[] = $token->text;
            continue;
        }

        if ($token->text === ',') {
            $caller_stack[] = $token->text;
            continue;
        }

        $caller_stack[] = $token->text;
        $var_names[]    = $token->text;
    }

    $caller = \implode('', $caller_stack);

    return [
        'caller'      => $caller,
        'var_names'   => $var_names,
    ];
}

/**
 * 指定された変数をダンプします。
 *
 * @param mixed ...$args dump対象の引数
 */
function d(...$args): void
{
    $backtraces = \debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
    $backtrace  = $backtraces['tacddd\\dd' === $backtraces[1]['function'] ?? '' ? 1 : 0];

    $file       = $backtrace['file'];
    $base_line  = $backtrace['line'];

    ['caller' => $caller, 'var_names' => $var_names] = get_args_definition_text(
        $file       = $backtrace['file'],
        $base_line  = $backtrace['line'],
    );

    echo '//==============================================', \PHP_EOL,
    \sprintf('%s(%s): %s', $file, $base_line, $caller), \PHP_EOL,
    '//==============================================', \PHP_EOL;

    foreach ($args as $idx => $arg) {
        $var_name   = $var_names[$idx];

        echo \sprintf('args #%d parameter: %s', $idx, $var_name), \PHP_EOL,
        \sprintf('value: %s', to_debug_string($arg, 4)), \PHP_EOL,
        '//----------------------------------------------', \PHP_EOL;
    }
}

/**
 * 変数に関する情報を文字列にして返します。
 *
 * @param  mixed           $var     変数に関する情報を文字列にしたい変数
 * @param  int             $depth   変数に関する情報を文字列にする階層の深さ
 * @param  null|array|bool $options オプション
 *                                  [
 *                                  'prettify'      => bool     出力結果をprettifyするかどうか
 *                                  'indent_level'  => int      prettify時の開始インデントレベル
 *                                  'indent_width'  => int      prettify時のインデント幅
 *                                  'object_detail' => bool     オブジェクト詳細情報に対してのみの表示制御
 *                                  'loaded_object' => object   現時点までに読み込んだことがあるobject
 *                                  ]
 * @return string          変数に関する情報
 */
function to_debug_string(mixed $var, int $depth = 0, array|bool|null $options = []): string
{
    if (\is_array($options)) {
        if (!isset($options['prettify'])) {
            $options['prettify']    = isset($options['indent_level']) || isset($options['indent_width']);
        }

        if (!isset($options['indent_level'])) {
            $options['indent_level']    = $options['prettify'] ? TO_DEBUG_STRING_DEFAULT_INDENT_LEVEL : null;
        }

        if (!isset($options['indent_width'])) {
            $options['indent_width']    = $options['prettify'] ? TO_DEBUG_STRING_DEFAULT_INDENT_WIDTH : null;
        }
    } elseif (\is_bool($options) && $options) {
        $options    = [
            'prettify'      => true,
            'indent_level'  => TO_DEBUG_STRING_DEFAULT_INDENT_LEVEL,
            'indent_width'  => TO_DEBUG_STRING_DEFAULT_INDENT_WIDTH,
        ];
    } else {
        $options    = [
            'prettify'      => false,
            'indent_level'  => null,
            'indent_width'  => null,
        ];
    }

    if (!isset($options['object_detail'])) {
        $options['object_detail']   = true;
    }

    if (!isset($options['loaded_object'])) {
        $options['loaded_object']   = (object) ['loaded' => []];
    }

    switch (\gettype($var)) {
        case 'boolean':
            return $var ? 'true' : 'false';
        case 'integer':
            return (string) $var;
        case 'double':
            if (false === \mb_strpos((string) $var, '.')) {
                return \sprintf('%s.0', $var);
            }

            return (string) $var;
        case 'string':
            return \sprintf('\'%s\'', $var);
        case 'array':
            if ($depth < 1) {
                return 'Array';
            }
            --$depth;

            $ret = [];

            foreach ($var as $key => $value) {
                $ret[] = \sprintf('%s => %s', to_debug_string($key), to_debug_string($value, $depth, $options));
            }

            return \sprintf('[%s]', \implode(', ', $ret));

        case 'object':
            $object_status = \sprintf('object(%s)#%d', $var::class, \spl_object_id($var));

            if ($depth < 1 || !$options['object_detail']) {
                return $object_status;
            }

            if (isset($options['loaded_object']->loaded[$object_status])) {
                return \sprintf('%s [displayed]', $object_status);
            }
            $options['loaded_object']->loaded[$object_status]   = $object_status;

            --$depth;

            $ro = new \ReflectionObject($var);

            $tmp_properties = [];

            foreach ($ro->getProperties() as $property) {
                $state                               = $property->isStatic() ? 'static' : 'dynamic';
                $modifier                            = $property->isPublic() ? 'public' : ($property->isProtected() ? 'protected' : ($property->isPrivate() ? 'private' : 'unknown modifier'));
                $tmp_properties[$state][$modifier][] = $property;
            }

            $properties = [];

            foreach (['static', 'dynamic'] as $state) {
                $state_text = $state === 'static' ? ' static' : '';

                foreach (['public', 'protected', 'private', 'unknown modifier'] as $modifier) {
                    foreach ($tmp_properties[$state][$modifier] ?? [] as $property) {
                        $property->setAccessible(true);
                        $properties[] = \sprintf('%s%s %s = %s', $modifier, $state_text, \sprintf('$%s', $property->getName()), to_debug_string($property->getValue($var), $depth, $options));
                    }
                }
            }

            return \sprintf('%s {%s}', $object_status, \implode(', ', $properties));

        case 'resource':
            return \sprintf('%s %s', \get_resource_type($var), $var);
        case 'resource (closed)':
            return \sprintf('resource (closed) %s', $var);
        case 'NULL':
            return 'NULL';
        case 'unknown type':
        default:
            return 'unknown type';
    }
}
