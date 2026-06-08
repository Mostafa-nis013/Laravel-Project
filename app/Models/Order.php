<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED    = 'shipped';
    const STATUS_DELIVERED  = 'delivered';
    const STATUS_CANCELLED  = 'cancelled';
    const STATUS_REFUNDED   = 'refunded';

    const STATUSES = [
        self::STATUS_PENDING    => 'Pending',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_SHIPPED    => 'Shipped',
        self::STATUS_DELIVERED  => 'Delivered',
        self::STATUS_CANCELLED  => 'Cancelled',
        self::STATUS_REFUNDED   => 'Refunded',
    ];

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
        'subtotal',
        'shipping_fee',
        'tax',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax'          => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING    => 'yellow',
            self::STATUS_PROCESSING => 'blue',
            self::STATUS_SHIPPED    => 'purple',
            self::STATUS_DELIVERED  => 'green',
            self::STATUS_CANCELLED  => 'red',
            self::STATUS_REFUNDED   => 'gray',
            default                 => 'gray',
        };
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }
}
