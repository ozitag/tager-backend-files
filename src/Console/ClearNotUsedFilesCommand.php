<?php

namespace OZiTAG\Tager\Backend\Files\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Console\Command;

class ClearNotUsedFilesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tager:clear-not-used-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear not used files from database';

    private function clearFilesFromDatabase()
    {
        if (Schema::hasTable('files') == false) {
            $this->error('Error: no table found "files"');
            return;
        }

        $count = DB::selectOne('SELECT COUNT(*) as count from files');
        $this->log('Files found: ' . $count->count);

        $tables = [];
        foreach (DB::select('SHOW TABLES') as $tableData) {
            $tableData = json_decode(json_encode($tableData), true);
            $keys = array_keys($tableData);
            $tables[] = $tableData[$keys[0]];
        }

        $foreignKeys = [];
        foreach ($tables as $tableName) {
            $tableForeignKeys = DB::select('SELECT TABLE_NAME,COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME="files" AND REFERENCED_TABLE_NAME = "' . $tableName . '";');
            if (!empty($tableForeignKeys)) {
                $foreignKeys = array_merge($foreignKeys, array_map(function ($key) {
                    return ['table' => $key->TABLE_NAME, 'column' => $key->COLUMN_NAME];
                }, $tableForeignKeys));
            }
        }

        $fileIds = [];
        foreach ($foreignKeys as $foreignKey) {
            $files = DB::select('SELECT `' . $foreignKey['column'] . '` as file_id FROM ' . $foreignKey['table'] .
                ' WHERE `' . $foreignKey['column'] . '` is not null');
            foreach ($files as $file) {
                $fileIds[] = $file->file_id;
            }
        }
        $fileIds = array_values(array_unique($fileIds));

        $this->log('Files used: ' . count($fileIds));

        $sqlQuery = 'DELETE FROM files WHERE id NOT in (' . implode(',', $fileIds) . ')';
        $deletedCount = DB::delete($sqlQuery);

        $this->log('Files deleted: ' . $deletedCount);
    }

    public function handle(FileRepository $fileRepository)
    {
        $this->clearFilesFromDatabase();

        /** @var File[] $files */
        $files = $fileRepository->all();
        $filesCount = count($files);

        $result = [];

        foreach ($files as $ind => $file) {
            $this->log('File ' . ($ind + 1) . ' / ' . count($files) . ', ID ' . $file->id . ': ', false);

            $scenario = $file->scenarioInstance(false);
            if (!$scenario) {
                $this->log('No Scenario');
                continue;
            }

            $filePath = $file->getPath();
            $filePathes = $scenario->getStorage()->getThumbnailPathes($file->hash, $scenario->shouldSaveOriginalFilename());

            $result = array_merge($result, $filePath ? [$filePath] : [], $filePathes);

            $this->log('OK');
        }

        $this->log('Found ' . count($result) . ' used files on storage');
    }
}
