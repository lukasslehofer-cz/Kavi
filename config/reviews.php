<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hlavní vypínač
    |--------------------------------------------------------------------------
    |
    | Naplánovaný běh reviews:send se přeskočí, dokud tohle není true. Ruční
    | spuštění příkazu funguje vždy, aby šel udělat dry-run před ostrým startem.
    |
    */

    'enabled' => env('REVIEWS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Milníky
    |--------------------------------------------------------------------------
    |
    | Kolikáté doručení spustí žádost o hodnocení. Platí zvlášť pro doručené
    | objednávky a zvlášť pro doručené zásilky předplatného. Po druhém doručení
    | zákazník zná kvalitu i spolehlivost, takže je to nejlepší okamžik zeptat se.
    |
    */

    'milestones' => [2, 6, 12, 24],

    /*
    |--------------------------------------------------------------------------
    | Časování
    |--------------------------------------------------------------------------
    |
    | Žádost odchází nejdřív 'delay_days' po doručení. Okno je rozsahové, takže
    | vynechaný běh cronu se dožene a nikdo nepropadne sítem. 'max_age_days' je
    | zároveň pojistka proti backfillu - u starších doručení se nic neodešle.
    |
    */

    'delay_days' => 7,
    'max_age_days' => 21,

    // Připomínka pro toho, kdo na žádost nereagoval (0 = vypnuto)
    'reminder_after_days' => 6,

    // Minimální odstup mezi dvěma žádostmi pro stejného člověka
    'min_days_between_requests' => 30,

    // Kdo jen kliknul a hodnocení nedokončil, dostane pokoj na tolik měsíců
    'click_cooldown_months' => 12,

];
