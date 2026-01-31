<?php

namespace App\Mail\Inventory;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IngredientCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $ingredient;
    public $creator;

    /**
     * @param $ingredient - The newly created ingredient object
     * @param $creator - The admin who created it
     */
    public function __construct($ingredient, $creator)
    {
        $this->ingredient = $ingredient;
        $this->creator = $creator;
    }

    public function build()
    {
        return $this->subject('New Ingredient Added: Action Required')
                    ->view('mail.inventory.ingredientCreated');
    }
}