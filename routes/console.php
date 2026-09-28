<?php

use Illuminate\Support\Facades\Schedule;

// Cron único no hPanel da Hostinger: * * * * * php .../artisan schedule:run
Schedule::command('app:backup-database')->dailyAt('03:00');

// LGPD: remove contatos sem interação há mais de 2 anos (ver Política de Privacidade)
Schedule::command('model:prune')->daily();
