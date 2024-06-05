<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property Carbon $visited_at timestamp when the save was last visited by the user
 */
class LastVisitedSaves extends Pivot
{
    protected $table = "last_visited_saves";
}
