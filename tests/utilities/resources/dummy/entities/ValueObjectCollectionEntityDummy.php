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

namespace tacddd\tests\utilities\resources\dummy\entities;

use tacddd\tests\utilities\resources\dummy\value_objects\GroupDummy;
use tacddd\tests\utilities\resources\dummy\value_objects\IdDummy;
use tacddd\tests\utilities\resources\dummy\value_objects\NameDummy;

final class ValueObjectCollectionEntityDummy
{
    public static function of(
        int $id,
        string $group,
        string $name,
    ): self {
        return new self(
            new IdDummy($id),
            new GroupDummy($group),
            new NameDummy($name),
        );
    }

    public function __construct(
        private IdDummy $id,
        private GroupDummy $group,
        private NameDummy $name,
    ) {
    }

    public function getId(): IdDummy
    {
        return $this->id;
    }

    public function getGroup(): GroupDummy
    {
        return $this->group;
    }

    public function getName(): NameDummy
    {
        return $this->name;
    }
}
