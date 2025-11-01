<?php

namespace App\Enum\Admin\Status;

enum StatusCicilan
{
    const UNPAID = 'unpaid';
    const LATE_PAYMENT = 'late_payment';
    const PAID = 'paid';
}
