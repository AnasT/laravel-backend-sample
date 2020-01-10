<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriberField extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriber_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['value', 'field_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function getValueAttribute($value)
    {
        switch ($this->field->type) {
            case FieldType::BOOLEAN:
                return filter_var(
                    $value,
                    FILTER_VALIDATE_BOOLEAN
                );
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = (string) $value;
    }
}
