<?php
/**
 *   _____          ____  ____  ____
 *  |_   ___ _  ___|  _ \|  _ \|  _ \
 *    | |/ _` |/ __| | | | | | | | | |
 *    | | (_| | (__| |_| | |_| | |_| |
 *    |_|\__,_|\___|____/|____/|____/
 *
 * @category    TacD
 * @package     TacD
 * @author      wakaba <wakabadou@gmail.com>
 * @copyright   Copyright (c) @2023  Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/). All rights reserved.
 * @license     http://opensource.org/licenses/MIT The MIT License.
 *              This software is released under the MIT License.
 * @varsion     1.0.0
 */

declare(strict_types=1);

$root_dir  = \dirname(__DIR__);

return PhpCsFixer\Finder::create()
->in($root_dir)         // 読み込み対象ディレクトリ
->notPath(\array_merge( // 除外対象ファイルパス
    include \sprintf('%s/.php-cs-fixer/.php-cs-fixer.not_path.php', $root_dir),
))
->exclude(\array_merge( // 除外対象ディレクトリ
    include \sprintf('%s/.php-cs-fixer/.php-cs-fixer.exclude.php', $root_dir),
));
