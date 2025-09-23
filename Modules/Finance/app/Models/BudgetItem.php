<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = ['budget_id', 'type', 'name', 'description', 'estimated_amount'];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
