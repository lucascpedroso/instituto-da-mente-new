<?php

return [
    // Destinatário das notificações de novos leads (formulários do site).
    'leads_to' => env('MAIL_LEADS_TO', env('MAIL_FROM_ADDRESS')),

    // Pasta dos backups do banco (fora da pasta pública).
    'backup_path' => env('BACKUP_PATH', storage_path('app/backups')),

    // Ativado apenas pelo comando app:export-static (pré-visualização no GitHub Pages).
    'static_preview' => false,
];
