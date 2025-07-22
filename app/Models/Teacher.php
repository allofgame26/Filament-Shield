<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher(){
        return $this->hasMany(homerooms::class,'teachers_id','id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }


    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams->contains($tenant);
    }

    public function team(){
        return $this->belongsToMany(Team::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_id', 'id');
    }

}
