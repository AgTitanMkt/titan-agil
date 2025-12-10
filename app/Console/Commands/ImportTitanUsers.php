<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportTitanUsers extends Command
{
    protected $signature = 'titan:import-users {file}';
    protected $description = 'Importa colaboradores do CSV Titan';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("Arquivo não encontrado: $file");
            return;
        }

        $csv = array_map('str_getcsv', file($file));
        $header = array_shift($csv);

        foreach ($csv as $line) {

            if (count($line) !== count($header)) {
                $this->warn("Linha ignorada por divergência de colunas: " . implode(" | ", $line));
                continue;
            }

            $data = array_combine($header, $line);

            // 1. Email
            $email = trim($data["EMAIL CORPORATIVO"]) ?: trim($data["EMAIL PESSOAL"]);
            if (!$email) continue;

            // Evitar duplicação
            if (User::where('email', $email)->exists()) continue;

            // 2. Departamento
            $department = $this->mapDepartment($data["SETOR"]);

            // 3. Cargo → position, cria se não existir
            $position = $this->getOrCreatePosition($data["CARGO"], $department);

            // 4. Plataforma
            $platform = $this->mapPlatform($data["SETOR"]);

            // 5. Status
            $status = strtolower($data["STATUS"]) === "ativo" ? "active" : "inactive";

            // 6. Criar usuário
            $user = User::create([
                "name"  => $data["NOME"],
                "email" => $email,
                "password" => Hash::make("12345678"),
            ]);

            // 7. Criar user_details
            DB::table("user_details")->insert([
                "user_id" => $user->id,
                "department_id" => $department,
                "position_id" => $position,
                "platform_id" => $platform,
                "personal_email" => $data["EMAIL PESSOAL"],
                "start_date" => $this->parseDate($data["DATA DE ADMISSÃO"]),
                "status" => $status,
                "created_at" => now(),
            ]);

            // 8. Role automática
            $role = $this->mapRole($data["SETOR"], $data["CARGO"]);
            if ($role) {
                DB::table("user_roles")->insert([
                    "user_id" => $user->id,
                    "role_id" => $role,
                    "created_at" => now(),
                ]);
            }

            $this->info("Importado: {$user->name}");
        }
    }

    private function mapDepartment($setor)
    {
        $map = [
            "COPY" => 1,
            "EDIÇÃO" => 2,
            "DESIGN" => 3,
            "YOUTUBE" => 4,
            "FACEBOOK" => 4,
            "TRÁFEGO" => 4,
            "GESTÃO" => 6,
            "FINANCEIRO" => 5,
            "DEV" => 8,
        ];

        return $map[strtoupper($setor)] ?? null;
    }

    private function getOrCreatePosition($cargo, $department)
    {
        if (!$cargo) return null;

        $cargo = trim($cargo);

        $existing = DB::table("positions")->where("name", $cargo)->first();
        if ($existing) return $existing->id;

        return DB::table("positions")->insertGetId([
            "name" => $cargo,
            "department_id" => $department,
            "created_at" => now()
        ]);
    }

    private function mapPlatform($setor)
    {
        $map = [
            "YOUTUBE" => 4,
            "FACEBOOK" => 1,
            "TIKTOK" => 2,
            "GOOGLE" => 3,
        ];

        return $map[strtoupper($setor)] ?? null;
    }

    private function mapRole($setor, $cargo)
    {
        $cargo = strtoupper($cargo);

        if (str_contains($cargo, "COPY")) return 2;
        if (str_contains($cargo, "EDITOR")) return 3;
        if (str_contains($cargo, "DEV")) return 4;
        if ($cargo === "HEAD") return 6;

        $setor = strtoupper($setor);
        if (in_array($setor, ["YOUTUBE", "FACEBOOK", "TIKTOK", "TRÁFEGO"])) return 9;

        return null;
    }

    private function parseDate($value)
    {
        $value = trim($value);

        if (!$value || $value == '-' || $value == '--' || $value == '00/00/0000') {
            return null;
        }

        // Se vier como dd/mm/yyyy → converter para yyyy-mm-dd
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            $parts = explode('/', $value);
            return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
        }

        // Se vier como yyyy-mm-dd → já está válido
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        return null; // qualquer outra coisa vira NULL
    }
}
