<?php

namespace App\Models;

use App\Models\Traits\CanEncryptField;
use Database\Factories\IconFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

/**
 * App\Models\Icon
 *
 * @property string $name
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TwoFAccount|null $twofaccount
 *
 * @method static \Database\Factories\IconFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Icon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Icon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Icon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Icon whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Icon whereUpdatedAt($value)
 */
class Icon extends Model
{
    /**
     * @use HasFactory<IconFactory>
     */
    use CanEncryptField, HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the twofaccount that owns the icon.
     *
     * @return BelongsTo<\App\Models\TwoFAccount, \App\Models\Icon>
     */
    public function twofaccount() : BelongsTo
    {
        return $this->belongsTo(TwoFAccount::class, 'name', 'icon');
    }

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * Get content attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getContentAttribute($value)
    {
        return $this->decryptOrReturn(base64_decode($value));
    }

    /**
     * Set content attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setContentAttribute($value)
    {
        // Encrypt if needed
        $this->attributes['content'] = $this->encryptOrReturn(base64_encode($value));
    }
}
