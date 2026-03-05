<?php

namespace App\Models\Project;

use App\Models\Project\Task\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectWbsItem extends Model {
    protected $table = 'dotp_project_eap_items';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'item_name',
        'number',
        'sort_order',
        'is_leaf',
        'identation',
        'parent_id',
        'name',
        'indentation'
    ];

    protected function level(): Attribute {
        return Attribute::make(
            get: static function ($value, $attributes) {
                $indentation = $attributes['identation'] ?? '';

                if (empty($indentation)) {
                    return 0;
                }

                $levelByString = substr_count(html_entity_decode($indentation), '&nbsp;&nbsp;&nbsp;');

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
            Task::class,
            'dotp_tasks_workpackages',
            'eap_item_id',
            'task_id'
        );
    }
}
