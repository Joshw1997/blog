<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $with = ['category', 'author'];

    protected $guarded = [];
    //protected $fillable = ['title', 'excerpt', 'body'];


// can be used to direct a different id not id
//    public function getRouteKeyName()
//    {
//        return 'slug';
//    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where(fn($query) =>
                $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%')
            )
        );

        $query->when($filters['category'] ?? false, fn($query, $category) =>
            // Below explains how to convert mysql search to eloquent and this is a quicker way to do it.

            $query->whereHas('category', fn($query) => 
                $query->where('slug', $category))
                
        );

// when in table plus or mysql you can search for a category against a post by doing.

// SELECT
//     *
// FROM
//     `posts`
// WHERE
//     EXISTS (
//         SELECT
//             *
//         FROM
//             `categories`
//         WHERE
//             `categories`.`id` = `posts`.`category_id`
//             and `categories`.`slug` = 'test1'
//     )
// ORDER BY
//     `created_at` DESC

// You then convert this into eloquent using the below
            // $query->when($filters['category'] ?? false, fn($query, $category) =>
            // $query
            //     ->whereExists(fn($query) =>
            //         $query->from('categories')
            //             ->whereColumn('categories.id', 'posts.category_id')
            //             ->where('categories.slug', $category))
//        );


        $query->when($filters['author'] ?? false, fn($query, $author) =>
            $query->whereHas('author', fn($query) => 
                $query->where('username', $author))
        );  


    }   

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function category()
    {
        // hasOne, hasMany, belongsTo, belongsToMany        
        return $this->belongsTo(category::class);
    }

    public function author() // the function looks for user_id but author_id does not exist.
    {
        return $this->belongsTo(User::class,'user_id');
        // we can override the forgein key by putting in our actual database key
    }

}
