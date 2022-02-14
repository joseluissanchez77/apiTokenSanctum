<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * table
     *
     * @var string
     */
    protected $table = 'courses';

    // /**
    //  * relations
    //  *
    //  * @var array
    //  */
    // protected $relations = ['status_id', 'institute_id'];

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'c_name',
        'c_description',
        'c_period',
        'c_numberStudent',
        'c_date_initial',
        'c_note_approved'
    ];



    /**
     * hidden
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
