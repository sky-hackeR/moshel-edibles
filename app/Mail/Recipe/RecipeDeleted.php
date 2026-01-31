<?php

namespace App\Mail\Recipe;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecipeDeleted extends Mailable
{
    use Queueable, SerializesModels;

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