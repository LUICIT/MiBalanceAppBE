<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait Versionable
{
    /**
     * Boot del trait: engancha a eventos del modelo.
     */
    public static function bootVersionable(): void
    {
        // Al crear, inicializa/incrementa versión
        static::creating(function (Model $model) {
            // -- Si viene null/0, comienza en 1
            $model->version = ($model->version ?? 0) + 1; // ↑ versión en create
        });

        // En updates normales (no delete/restore), incrementa versión
        static::updating(function (Model $model) {
            // -- Evita incrementar en "touch" únicamente: si no hay cambios, no sube
            if (count($model->getDirty()) > 0) {
                $current = (int) ($model->getOriginal('version') ?? 0);
                $model->version = $current + 1; // ↑ versión en update
            }
        });

        // Soft delete: el evento deleting ocurre antes del UPDATE de deleted_at
        static::deleting(function (Model $model) {
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                // -- Borrado permanente: opcional subir versión; generalmente no necesario
                return;
            }
            $current = (int) ($model->getOriginal('version') ?? $model->version ?? 0);
            $model->version = $current + 1; // ↑ versión junto con el update de deleted_at
        });

        // Restore (soft delete → activo): el evento restoring ocurre antes del UPDATE
        static::restoring(function (Model $model) {
            $current = (int) ($model->getOriginal('version') ?? $model->version ?? 0);
            $model->version = $current + 1; // ↑ versión en restore
        });
    }
}
