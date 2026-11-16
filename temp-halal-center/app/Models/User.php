<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'sector_item_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    public function directorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SectorItem::class, 'sector_item_id');
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->roles->first()?->name ?? '-';
    }

    public function isAdminDirektorat(): bool
    {
        if ($this->hasRole('AdminDirektorat')) {
            return true;
        }

        $directorateSlugs = [
            'industri-produk-halal',
            'jasa-keuangan-syariah',
            'keuangan-sosial-syariah',
            'bisnis-kewirausahaan-syariah',
            'infrastruktur-ekosistem-syariah',
        ];

        foreach ($this->roles as $role) {
            $slug = \Illuminate\Support\Str::slug($role->name);
            $slug = preg_replace('/^admin[-_]?/', '', $slug);
            if (in_array($slug, $directorateSlugs)) {
                return true;
            }
        }

        return false;
    }

    public function getAssignedDirectorateAttribute(): ?SectorItem
    {
        if ($this->directorate) {
            return $this->directorate;
        }

        if ($this->sector_item_id) {
            return SectorItem::find($this->sector_item_id);
        }

        foreach ($this->roles as $role) {
            $slug = \Illuminate\Support\Str::slug($role->name);
            $slug = preg_replace('/^admin[-_]?/', '', $slug);
            $sector = SectorItem::where('slug', $slug)->first();
            if ($sector) {
                return $sector;
            }
        }

        return null;
    }

    public function canAccessDirectorate(SectorItem|string|int|null $directorate): bool
    {
        if ($this->hasAnyRole(['developer', 'superadmin'])) {
            return true;
        }

        if (!$this->isAdminDirektorat()) {
            return false;
        }

        $myDirectorate = $this->assigned_directorate;
        if (!$myDirectorate) {
            return false;
        }

        if ($directorate instanceof SectorItem) {
            return (int) $myDirectorate->id === (int) $directorate->id;
        }

        if (is_numeric($directorate)) {
            return (int) $myDirectorate->id === (int) $directorate;
        }

        if (is_string($directorate)) {
            $slug = \Illuminate\Support\Str::slug($directorate);
            return $myDirectorate->slug === $slug;
        }

        return false;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
