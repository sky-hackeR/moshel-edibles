<?php

namespace App\Services\BulkOperations\Workbook;

class WorksheetDefinition
{
    /**
     * Worksheet title.
     */
    protected string $title;

    /**
     * Worksheet subtitle.
     */
    protected ?string $subtitle = null;

    /**
     * Theme colour.
     */
    protected ?string $theme = null;

    /**
     * @var ColumnDefinition[]
     */
    protected array $columns = [];

    /**
     * Sample rows.
     */
    protected array $samples = [];

    /**
     * Worksheet instructions.
     */
    protected array $instructions = [];

    /**
     * Freeze header row.
     */
    protected bool $freezeHeader = true;

    /**
     * Enable Auto Filter.
     */
    protected bool $autoFilter = true;

    /**
     * Protect worksheet.
     */
    protected bool $protect = false;

    /**
     * Hide lookup sheets.
     */
    protected bool $hideLookups = true;

    /**
     * Show instruction sheet.
     */
    protected bool $showInstructions = true;

    /**
     * Show sample rows.
     */
    protected bool $showSample = true;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function make(string $title): self
    {
        return new self($title);
    }

    public function subtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function theme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @param ColumnDefinition[] $columns
     */
    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function addColumn(ColumnDefinition $column): self
    {
        $this->columns[] = $column;

        return $this;
    }

    public function samples(array $rows): self
    {
        $this->samples = $rows;

        return $this;
    }

    public function addSample(array $row): self
    {
        $this->samples[] = $row;

        return $this;
    }

    public function instructions(array $instructions): self
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function freezeHeader(bool $value = true): self
    {
        $this->freezeHeader = $value;

        return $this;
    }

    public function autoFilter(bool $value = true): self
    {
        $this->autoFilter = $value;

        return $this;
    }

    public function protect(bool $value = true): self
    {
        $this->protect = $value;

        return $this;
    }

    public function hideLookups(bool $value = true): self
    {
        $this->hideLookups = $value;

        return $this;
    }

    public function showInstructions(bool $value = true): self
    {
        $this->showInstructions = $value;

        return $this;
    }

    public function showSample(bool $value = true): self
    {
        $this->showSample = $value;

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    /**
     * @return ColumnDefinition[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getSamples(): array
    {
        return $this->samples;
    }

    public function getInstructions(): array
    {
        return $this->instructions;
    }

    public function shouldFreezeHeader(): bool
    {
        return $this->freezeHeader;
    }

    public function shouldAutoFilter(): bool
    {
        return $this->autoFilter;
    }

    public function shouldProtect(): bool
    {
        return $this->protect;
    }

    public function shouldHideLookups(): bool
    {
        return $this->hideLookups;
    }

    public function shouldShowInstructions(): bool
    {
        return $this->showInstructions;
    }

    public function shouldShowSample(): bool
    {
        return $this->showSample;
    }
}