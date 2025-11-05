<?php

namespace App\Filament\Clusters\Website\Pages;

use App\Filament\Clusters\Website\WebsiteCluster;
use App\Models\HomepageContent;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Actions\ButtonAction;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class HomepageContentPage extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedHome;
    protected static \BackedEnum|string|null $activeNavigationIcon = Heroicon::Home;

    protected static ?string $navigationLabel = 'Homepage Text';

    protected static ?string $title = 'Homepage Text';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.homepage-content-page';

    public ?HomepageContent $record = null;

    public ?array $data = [];

    public function mount(): void
    {
        // Ensure there is always exactly one record to edit
        $this->record = HomepageContent::query()->first();
        if (! $this->record) {
            $this->record = HomepageContent::create([]);
        }
        $this->data = $this->record->toArray();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Hero')
                    ->schema([
                        Forms\Components\TextInput::make('hero_title')->label('Title')->nullable(),
                        Forms\Components\Textarea::make('hero_subtitle')->label('Subtitle')->columnSpanFull(),
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('hero_cta_experience')->label('CTA: Experience')->maxLength(100),
                            Forms\Components\TextInput::make('hero_cta_contact')->label('CTA: Contact')->maxLength(100),
                        ]),
                    ])->columns(1),
                Section::make('Sytatsu')
                    ->schema([
                        Forms\Components\Textarea::make('sytatsu_text')->label('Paragraph')->columnSpanFull(),
                    ]),
                Section::make('About')
                    ->schema([
                        Forms\Components\TextInput::make('about_title')->label('Title'),
                        Forms\Components\Textarea::make('about_paragraph')->label('Paragraph')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                Section::make('Section Titles')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('skills_title')->label('Skills Title'),
                            Forms\Components\TextInput::make('experience_title')->label('Experience Title'),
                            Forms\Components\TextInput::make('contact_title')->label('Contact Title'),
                        ]),
                    ]),
                Section::make('Footer')
                    ->schema([
                        Forms\Components\TextInput::make('footer_text')->label('Footer Text'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->icon('heroicon-m-check-circle')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $payload = $this->data ?? [];

        $this->record->fill($payload);
        $this->record->save();

        $this->dispatch('saved');
        Notification::make()->title('Saved successfully')->success()->send();
    }
}
