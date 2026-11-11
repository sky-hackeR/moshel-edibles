<?php

namespace App\Mail\Recipe;

use App\Mail\QueuedMailable;

class RecipeDeleted extends QueuedMailable
{
    public $recipeName;
    public $user;

    public function __construct($recipeName, $user)
    {
        $this->recipeName = $recipeName;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Important: Recipe Deleted')
                    ->view('mail.recipe.recipeDeleted');
    }
}