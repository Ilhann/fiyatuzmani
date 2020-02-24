<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ["productURL", "provider", "title", "last_receive", "productid", "source"];
}
