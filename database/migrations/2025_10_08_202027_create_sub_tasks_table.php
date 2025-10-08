    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::disableForeignKeyConstraints();

            Schema::create('sub_tasks', function (Blueprint $table) {
                $table->id();

                $table->foreignId('task_id')
                    ->constrained('tasks')
                    ->onDelete('cascade'); // se a tarefa for apagada, apaga as subtarefas também

                $table->longText('description');

                $table->foreignId('executed_by')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null'); // mantém histórico mesmo se user for deletado

                $table->foreignId('revised_by')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null');

                $table->string('status');
                $table->dateTime('due_date');

                $table->foreignId('role_id')
                    ->nullable()
                    ->constrained('roles')
                    ->onDelete('set null');

                $table->timestamps();
            });


            Schema::enableForeignKeyConstraints();
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('sub_tasks', function (Blueprint $table) {
                $table->dropForeign(['executed_by', 'revised_by', 'role_id']);
            });
            Schema::dropIfExists('sub_tasks');
        }
    };
