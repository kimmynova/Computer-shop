<?php declare(strict_types=1);

namespace App\Enums\Inventory;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Status extends Enum
{
    const InStock = 0;
    const OutOfStock = 1;
    const LowStock = 2;
}
