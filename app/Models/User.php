<?php

namespace App\Models;

use App\Enum\UserStateEnum;
use App\Enum\RoleGroupEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'funcionario_id',
        'state',
        'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'state' => UserStateEnum::class
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::upper($value),
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
        );
    }

    public function scopeAtivos($query)
    {
        $query->where('state', 'ATIVO');
    }

    public function scopeInativos($query)
    {
        $query->where('state', '!=', UserStateEnum::INATIVO->value);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class)->withoutGlobalScopes();
    }

    public function centroCustos()
    {
        return $this->belongsToMany(CentroCusto::class)->withoutGlobalScopes();
    }

    public static function queryFiltradosPorPermissao()
    {
        $userRoles = User::find(auth()->id())->roles->pluck('name');
        $rolesParam = $userRoles->contains(RoleGroupEnum::Admin->name) ? Role::all()->pluck('name') : Role::where('id', '!=', RoleGroupEnum::Admin->value)->get()->pluck('name');

        return User::with('roles')
            ->role($rolesParam)
            ->whereHas('centroCustos', function (Builder $query) {
                $query->whereIn('centro_custo_id', function ($query) {
                    $query->selectRaw('centro_custo_user.centro_custo_id')
                        ->from('centro_custo_user')
                        ->where('centro_custo_user.user_id', auth()->id());
                });
            });
    }

    public static function filtradosPorPermissao()
    {
        return User::queryFiltradosPorPermissao()->get();
    }
}
