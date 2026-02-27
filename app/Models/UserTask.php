<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    protected $fillable = [
        'user_id',
        'sub_task_id',
        'status',
        'started_at',
        'completed_at',
    ];

    public const STATUS = [
        'ASSIGNED' => 'ASSIGNED',
        'IN_PROGRESS' => 'IN_PROGRESS',
        'DONE' => 'DONE',
        'REJECTED' => 'REJECTED',
        'REVIEW' => 'REVIEW',
    ];

    protected static function booted()
    {

        static::created(function ($assignment) {

            // Só executa se foi criado como ASSIGNED
            if ($assignment->status !== self::STATUS['ASSIGNED']) {
                return;
            }

            $subtask = $assignment->subTask()->with('assignments.user.roles')->first();

            if (!$subtask) {
                return;
            }

            $assignments = $subtask->assignments;

            $hasCopy = $assignments->contains(function ($a) {
                return $a->status === self::STATUS['ASSIGNED'] &&
                    $a->user->roles->contains('title', 'COPYWRITER');
            });

            $hasEditor = $assignments->contains(function ($a) {
                return $a->status === self::STATUS['ASSIGNED'] &&
                    $a->user->roles->contains('title', 'EDITOR');
            });

            if ($hasCopy && $hasEditor && $subtask->status === SubTask::STATUS['CREATED']) {

                $subtask->update([
                    'status' => SubTask::STATUS['PENDING']
                ]);
            }
        });

        static::updated(function ($assignment) {

            // Só executa se status mudou
            if (!$assignment->wasChanged('status')) {
                return;
            }

            // Só executa se virou DONE
            if ($assignment->status !== self::STATUS['DONE']) {
                return;
            }

            $subtask = $assignment->subTask()->with('assignments.user.roles')->first();

            if (!$subtask) {
                return;
            }

            // Pega assignments obrigatórios
            $assignments = $subtask->assignments;

            $copyDone = $assignments->contains(function ($a) {
                return $a->status === self::STATUS['DONE'] &&
                    $a->user->roles->contains('title', 'COPYWRITER');
            });

            $editorDone = $assignments->contains(function ($a) {
                return $a->status === self::STATUS['DONE'] &&
                    $a->user->roles->contains('title', 'EDITOR');
            });

            if ($subtask->status !== self::STATUS['REVIEW'] && $copyDone && $editorDone) {
                // Atualiza SubTask
                $subtask->update([
                    'status' => SubTask::STATUS['REVIEW']
                ]);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subTask()
    {
        return $this->belongsTo(SubTask::class);
    }
}
