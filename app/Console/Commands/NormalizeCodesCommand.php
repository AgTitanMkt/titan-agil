<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\RedtrackReport;

class NormalizeCodesCommand extends Command
{
    protected $signature = 'normalize:codes';
    protected $description = 'Normaliza códigos de tasks e redtrack_reports removendo espaços e ajustando caixa';

    public function handle()
    {
        $this->info('Normalizando tasks...');
        Task::chunk(1000, function ($tasks) {
            foreach ($tasks as $task) {
                $normalized = strtolower(str_replace(' ', '', $task->code));
                if ($task->normalized_code !== $normalized) {
                    $task->normalized_code = $normalized;
                    $task->save();
                }
            }
        });

        $this->info('Normalizando redtrack_reports...');
        RedtrackReport::chunk(1000, function ($reports) {
            foreach ($reports as $r) {
                $normalized = strtolower(str_replace(' ', '', $r->name));
                if ($r->normalized_rt_ad !== $normalized) {
                    $r->normalized_rt_ad = $normalized;
                    $r->save();
                }
            }
        });

        $this->info('Normalização concluída com sucesso!');
    }
}
