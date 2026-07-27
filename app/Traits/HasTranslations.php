<?php

namespace App\Traits;

use App\Models\Bahasa;

trait HasTranslations
{
    protected function getActiveBahasas()
    {
        return Bahasa::where('status', 'active')->orderBy('is_default', 'desc')->get();
    }

    protected function syncTranslations($model, $request, $transFields)
    {
        if (!$request->has('translations')) return;

        foreach ($request->translations as $bahasa_id => $trans) {
            $hasContent = false;
            foreach ($transFields as $field) {
                if (!empty($trans[$field])) {
                    $hasContent = true;
                    break;
                }
            }

            if ($hasContent) {
                $data = ['bahasa_id' => $bahasa_id];
                foreach ($transFields as $field) {
                    $data[$field] = $trans[$field] ?? null;
                }
                $model->translations()->updateOrCreate(['bahasa_id' => $bahasa_id], $data);
            }
        }
    }
}