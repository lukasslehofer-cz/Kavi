<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $pattern = '/\s*(m\s*\.?\s*n\s*\.?\s*m\s*\.?|m\.?\s*a\.?\s*s\.?\s*l\.?|masl|meters?|m)\s*$/iu';

        DB::table('products')->orderBy('id')->chunkById(200, function ($rows) use ($pattern) {
            foreach ($rows as $row) {
                if (empty($row->attributes)) {
                    continue;
                }
                $attrs = json_decode($row->attributes, true);
                if (!is_array($attrs)) {
                    continue;
                }

                $changed = false;
                foreach (['altitude', 'altitude_en'] as $key) {
                    if (!empty($attrs[$key]) && is_string($attrs[$key])) {
                        $cleaned = trim(preg_replace($pattern, '', $attrs[$key]));
                        if ($cleaned !== $attrs[$key]) {
                            $attrs[$key] = $cleaned;
                            $changed = true;
                        }
                    }
                }

                if ($changed) {
                    DB::table('products')->where('id', $row->id)
                        ->update(['attributes' => json_encode($attrs, JSON_UNESCAPED_UNICODE)]);
                }
            }
        });
    }

    public function down(): void
    {
        // Nevratné: neumíme zpětně rozhodnout, který produkt měl "m n.m." a který ne.
    }
};
