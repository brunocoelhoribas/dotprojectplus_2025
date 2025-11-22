<?php

namespace App\Models\Project;

use App\Models\Project\Task\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectWbsItem extends Model {
    use HasFactory;

    protected $table = 'dotp_project_eap_items';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'item_name', // ou 'description' no legado? Verifique o nome da coluna
        'number', // ex: 1.1, 1.2
        'sort_order',
        'is_leaf',
        'identation',
        'parent_id'
    ];


    protected function level(): Attribute
    {
        return Attribute::make(
            get: static function ($value, $attributes) {
                $indentation = $attributes['identation'] ?? '';

                if (empty($indentation)) {
                    return 0;
                }

                $decoded = html_entity_decode($indentation);
                $levelByString = substr_count($indentation, '&nbsp;&nbsp;&nbsp;');

                if ($levelByString > 0) {
                    return $levelByString;
                }

                return (int)(strlen($indentation) / 18);
            }
        );
    }

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function tasks(): BelongsToMany {
        return $this->belongsToMany(
            Task::class,                    // Model final (Tarefa)
            'dotp_tasks_workpackages',      // Tabela de ligação (que achamos na imagem)
            'eap_item_id',                  // Chave estrangeira do WBS nesta tabela
            'task_id'                       // Chave estrangeira da Task nesta tabela
        );
    }
}
