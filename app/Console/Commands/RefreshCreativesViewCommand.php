<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshCreativesViewCommand extends Command
{
    protected $signature = 'view:refresh-creatives';
    protected $description = 'Atualiza a tabela vw_creatives_performance com dados do RedTrack, ClickUp e agentes responsáveis.';

    public function handle()
    {
        $this->info('🔄 Limpando tabela vw_creatives_performance...');
        DB::table('vw_creatives_performance')->truncate();

        $this->info('🚀 Inserindo dados combinados (RedTrack + ClickUp + Responsáveis)...');

        DB::statement('
            INSERT INTO vw_creatives_performance (
                task_id,
                creative_code,
                origem,
                user_id,
                agent_name,
                redtrack_id,
                rt_ad,
                source,
                clicks,
                conversions,
                roi,
                cost,
                profit,
                revenue,
                roas,
                ctr,
                cpm,
                redtrack_date,
                created_at,
                updated_at
            )
            SELECT
                t.id AS task_id,
                t.code AS creative_code,
                t.title AS task_name,
                u.id AS user_id,
                u.name AS agent_name,
                r.id AS redtrack_id,
                r.name AS rt_ad,
                r.source AS source,
                r.clicks,
                r.conversions,
                r.roi,
                r.cost,
                r.profit,
                (r.profit + r.cost) AS revenue,
                CASE WHEN r.cost > 0 THEN (r.profit / r.cost) ELSE 0 END AS roas,
                NULL AS ctr,
                NULL AS cpm,
                r.created_at AS redtrack_date,
                NOW(),
                NOW()
            FROM redtrack_reports r
            INNER JOIN tasks t 
                ON r.normalized_rt_ad = t.normalized_code
            INNER JOIN sub_tasks st 
                ON st.task_id = t.id
            INNER JOIN user_tasks ut 
                ON ut.sub_task_id = st.id
            INNER JOIN users u 
                ON u.id = ut.user_id
            GROUP BY
                t.id, u.id, r.id
        ');

        $this->info('✅ Tabela vw_creatives_performance atualizada com sucesso!');
    }
}
