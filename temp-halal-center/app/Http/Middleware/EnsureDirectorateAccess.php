<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\DataDirektoratController;
use App\Models\SectorItem;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDirectorateAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        // Superadmin and Developer have unrestricted access across all directorates
        if ($user->hasAnyRole(['developer', 'superadmin'])) {
            return $next($request);
        }

        // Check {item_key} for Data Direktorat routes
        $itemKey = $request->route('item_key');
        if ($itemKey) {
            if (!$user->isAdminDirektorat()) {
                abort(403, 'Akses ditolak. Anda tidak memiliki hak akses admin direktorat.');
            }

            $meta = DataDirektoratController::findItemMeta($itemKey);
            if ($meta) {
                $userDirectorate = $user->assigned_directorate;
                if (!$userDirectorate) {
                    abort(403, 'Akses ditolak. Akun Anda belum terhubung dengan direktorat manapun.');
                }

                $subModuleSlug = \Illuminate\Support\Str::slug($meta['sub_module']);
                if ($subModuleSlug !== $userDirectorate->slug) {
                    abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data direktorat lain.');
                }
            } else {
                // Check if it is a dynamic sub-module
                $dynamicSubModule = \App\Models\DynamicSubModule::where('slug', $itemKey)->first();
                if ($dynamicSubModule) {
                    if (!$user->canAccessDirectorate($dynamicSubModule->sector_item_id)) {
                        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses sub-modul direktorat lain.');
                    }
                } else {
                    abort(404, "Modul data direktorat '{$itemKey}' tidak ditemukan.");
                }
            }
        }

        // Check {dynamic_sub_module} parameter
        $dynamicParam = $request->route('dynamic_sub_module') ?? $request->route('sub_module');
        if ($dynamicParam) {
            if (!$user->isAdminDirektorat()) {
                abort(403, 'Akses ditolak.');
            }

            if (!$dynamicParam instanceof \App\Models\DynamicSubModule) {
                $dynamicParam = \App\Models\DynamicSubModule::find($dynamicParam);
            }

            if ($dynamicParam && !$user->canAccessDirectorate($dynamicParam->sector_item_id)) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses sub-modul direktorat lain.');
            }
        }

        // Check {user} parameter for User Management
        $targetUser = $request->route('user');
        if ($targetUser) {
            if (!$user->isAdminDirektorat()) {
                abort(403, 'Akses ditolak.');
            }

            if (!$targetUser instanceof User) {
                $targetUser = User::find($targetUser);
            }

            if ($targetUser) {
                if ((int) $targetUser->sector_item_id !== (int) $user->sector_item_id) {
                    abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses pengguna direktorat lain.');
                }
            }
        }

        // Check {sector_item} parameter for SectorItem Management
        $sectorItem = $request->route('sector_item');
        if ($sectorItem) {
            if (!$user->isAdminDirektorat()) {
                abort(403, 'Akses ditolak.');
            }

            if (!$sectorItem instanceof SectorItem) {
                $sectorItem = SectorItem::find($sectorItem);
            }

            if ($sectorItem && (int) $sectorItem->id !== (int) $user->sector_item_id) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data direktorat lain.');
            }
        }

        return $next($request);
    }
}
