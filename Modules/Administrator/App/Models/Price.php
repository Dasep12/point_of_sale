<?php

namespace Modules\Administrator\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Price extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_harga';
    protected $primaryKey = 'id';
    protected $fillable = [
        'member_id',
        'material_id',
        'harga_jual',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];
}
