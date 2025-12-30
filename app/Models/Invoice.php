<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'customer_name',
        'customer_contact',
        'invoice_date',
        'total',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $lastInvoice = self::latest('id')->first();
        $number = $lastInvoice ? $lastInvoice->id + 1 : 1;
        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function calculateTotal(): void
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }
}
