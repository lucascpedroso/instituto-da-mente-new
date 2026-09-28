<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Backup do banco em SQL compactado, sem depender de `mysqldump`/`exec`
 * (que podem estar indisponíveis na hospedagem compartilhada).
 * Os arquivos ficam em storage/app/backups (fora da pasta pública).
 */
class BackupDatabase extends Command
{
    protected $signature = 'app:backup-database {--keep=14 : Quantos backups manter} {--connection= : Conexão do banco (padrão: a do .env)}';

    protected $description = 'Gera um backup .sql.gz do banco de dados e remove os mais antigos';

    public function handle(): int
    {
        $dir = config('instituto.backup_path');
        File::ensureDirectoryExists($dir);

        $connection = DB::connection($this->option('connection'));
        $file = $dir.'/backup-'.now()->format('Y-m-d-His').'.sql.gz';

        if ($connection->getDriverName() === 'sqlite') {
            if (! File::exists($connection->getDatabaseName())) {
                $this->error('Banco SQLite em memória não pode ser copiado.');

                return self::FAILURE;
            }

            File::put($file, gzencode(File::get($connection->getDatabaseName())));
        } else {
            $this->dumpMysql($connection, $file);
        }

        $backups = collect(File::glob($dir.'/backup-*.sql.gz'))->sort()->values();
        $backups->slice(0, max(0, $backups->count() - (int) $this->option('keep')))->each(fn ($old) => File::delete($old));

        $this->info('Backup criado: '.basename($file));

        return self::SUCCESS;
    }

    private function dumpMysql(Connection $db, string $file): void
    {
        $gz = gzopen($file, 'w6');
        $pdo = $db->getPdo();

        gzwrite($gz, '-- Backup Instituto da Mente — '.now()->toDateTimeString()."\nSET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

        foreach ($db->select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"') as $row) {
            $table = array_values((array) $row)[0];
            $create = array_values((array) $db->selectOne("SHOW CREATE TABLE `{$table}`"))[1];

            gzwrite($gz, "DROP TABLE IF EXISTS `{$table}`;\n{$create};\n\n");

            foreach ($db->table($table)->cursor() as $record) {
                $values = array_map(fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v), (array) $record);
                gzwrite($gz, "INSERT INTO `{$table}` VALUES (".implode(',', $values).");\n");
            }

            gzwrite($gz, "\n");
        }

        gzwrite($gz, "SET FOREIGN_KEY_CHECKS=1;\n");
        gzclose($gz);
    }
}
