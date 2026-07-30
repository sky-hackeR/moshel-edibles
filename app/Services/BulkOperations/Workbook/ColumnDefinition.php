<?php

namespace App\Services\BulkOperations\Workbook;

class ColumnDefinition
{
    /**
     * Column heading.
     */
    protected string $title;

    /**
     * Required?
     */
    protected bool $required = false;

    /**
     * Editable?
     */
    protected bool $editable = true;

    /**
     * Hidden?
     */
    protected bool $hidden = false;

    /**
     * Preferred width.
     */
    protected ?int $width = null;

    /**
     * Excel format.
     */
    protected ?string $format = null;

    /**
     * Theme override.
     */
    protected ?string $theme = null;

    /**
     * Dropdown values.
     */
    protected array $dropdown = [];

    /**
     * Cell comment.
     */
    protected ?string $comment = null;

    /**
     * Instruction shown to user.
     */
    protected ?string $instruction = null;

    /**
     * Placeholder/example.
     */
    protected mixed $example = null;

    /**
     * Formula.
     */
    protected ?string $formula = null;

    protected int $formulaStartRow = 2;

    protected int $formulaEndRow = 1000;

    /**
     * Lookup sheet name.
     */
    protected ?string $lookup = null;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function make(string $title): self
    {
        return new self($title);
    }

    public function required(bool $value = true): self
    {
        $this->required = $value;

        return $this;
    }

    public function editable(bool $value = true): self
    {
        $this->editable = $value;

        return $this;
    }

    public function hidden(bool $value = true): self
    {
        $this->hidden = $value;

        return $this;
    }

    public function width(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function theme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function dropdown(array $values): self
    {
        $this->dropdown = $values;

        return $this;
    }

    public function comment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function instruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function example(mixed $value): self
    {
        $this->example = $value;

        return $this;
    }

    public function lookup(string $sheet): self
    {
        $this->lookup = $sheet;

        return $this;
    }

    public function formula(
        string $formula,
        int $startRow = 2,
        int $endRow = 1000
    ): self {

        $this->formula = $formula;

        $this->formulaStartRow = $startRow;

        $this->formulaEndRow = $endRow;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isEditable(): bool
    {
        return $this->editable;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function getDropdown(): array
    {
        return $this->dropdown;
    }

    public function hasDropdown(): bool
    {
        return ! empty($this->dropdown);
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function getExample(): mixed
    {
        return $this->example;
    }

    public function getLookup(): ?string
    {
        return $this->lookup;
    }

    public function hasFormula(): bool
    {
        return $this->formula !== null;
    }

    public function getFormula(): ?string
    {
        return $this->formula;
    }

    public function getFormulaStartRow(): int
    {
        return $this->formulaStartRow;
    }

    public function getFormulaEndRow(): int
    {
        return $this->formulaEndRow;
    }
}