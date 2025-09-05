<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VersionedObserver
{
    // -- Registra un cambio en la tabla change_log
    protected function log(Model $m, string $op): void
    {
        // -- Construir diff (solo campos cambiados) con pares old/new
        $ignored = ['created_at', 'updated_at', 'deleted_at']; // campos de sistema a ignorar
        $diff = [];

        if ($op === 'create') {
            // En create, todos los atributos son "nuevos"
            foreach ($m->getAttributes() as $key => $newValue) {
                if (in_array($key, $ignored, true)) {
                    continue;
                }
                $diff[$key] = [
                    'old' => null,
                    'new' => $newValue,
                ];
            }
        } else {
            // En update/delete/restore usamos getChanges() para detectar cambios de la última operación
            $changes = $m->getChanges(); // valores "new"
            $original = $m->getOriginal(); // valores "old"

            foreach ($changes as $key => $newValue) {
                if (in_array($key, $ignored, true)) {
                    continue;
                }
                $diff[$key] = [
                    'old' => $original[$key] ?? null,
                    'new' => $newValue,
                ];
            }
        }
        DB::table('change_logs')->insert([
            'user_id'     => auth()->id() ?? 0,
            'table_name'  => $m->getTable(),
            'table_id'    => $m->getKey(),
            'version'     => (int)($m->version ?? 1),
            'operation'   => $op, // 'create' | 'update' | 'delete' | 'restore'
            'diff'        => json_encode($diff, JSON_UNESCAPED_UNICODE),
            'change_date' => now(),
        ]);
    }


    // -- Altas y modificaciones
    public function created(Model $m): void
    {
        $this->log($m, 'create');
    }

    public function updated(Model $m): void
    {
        $this->log($m, 'update');
    }

    // -- Soft delete
    public function deleted(Model $m): void
    {
        $this->log($m, 'delete');
    }

    // -- Restore
    public function restored(Model $m): void
    {
        $this->log($m, 'restore');
    }
}
