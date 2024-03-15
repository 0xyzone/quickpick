<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The OrderStatus enum.
 *
 * @method static self Pending()
 * @method static self Preparing()
 * @method static self Ready()
 * @method static self Out_Delivery()
 * @method static self Delivered()
 * @method static self Cancelled()
 */
class OrderStatus extends Enum
{
    const Pending = 'pending';
    const Preparing = 'preparing';
    const Ready = 'ready';
    const Out_Delivery = 'out_delivery';
    const Delivered = 'delivered';
    const Cancelled = 'cancelled';
}
