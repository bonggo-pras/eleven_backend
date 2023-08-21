<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketingReseller extends Model
{
    use HasFactory;

    protected $table = 'marketing_reseller';

    protected $fillable = [
        'customer_id',
        'marketing_id'
    ];

    /**
     * The customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne(Customer::modelClass(), 'id', 'customer_id');
    }

    /**
     * The marketing.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function marketing()
    {
        return $this->hasOne(Customer::modelClass(), 'id', 'marketing_id');
    }
}
