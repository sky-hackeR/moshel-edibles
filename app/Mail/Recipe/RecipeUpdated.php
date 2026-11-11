<?php

namespace App\Mail\Recipe;

use App\Mail\QueuedMailable;

class RecipeUpdated extends QueuedMailable
{
    public $recipe;
    public $user;

    public function __construct($recipe, $user)
    {
        $this->recipe = $recipe;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Formula Updated: ' . $this->recipe->product->name)
                    ->view('mail.recipe.recipeUpdated');
    }
}