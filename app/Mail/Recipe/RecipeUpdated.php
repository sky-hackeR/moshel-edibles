<?php

namespace App\Mail\Recipe;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecipeUpdated extends Mailable
{
    use Queueable, SerializesModels;

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