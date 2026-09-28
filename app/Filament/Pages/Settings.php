<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\AdminOnly;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

/**
 * Dados de contato, redes sociais e códigos de rastreamento editáveis sem desenvolvedor.
 */
class Settings extends Page
{
    use AdminOnly;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Configurações do site';

    protected static ?string $navigationLabel = 'Dados e integrações';

    protected static ?string $slug = 'configuracoes';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::values());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Contato')
                    ->description('Aparece no rodapé, na página de contato e nos botões de WhatsApp.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('whatsapp')->label('WhatsApp (somente números, com 55 + DDD)')->required()->regex('/^55\d{10,11}$/')
                                ->validationMessages(['regex' => 'Use o formato 5519999999999.']),
                            TextInput::make('phone')->label('Telefone exibido')->required()->placeholder('(19) 99160-7338'),
                            TextInput::make('email')->label('E-mail de contato')->email(),
                            TextInput::make('instagram')->label('Instagram (usuário, sem @)'),
                        ]),
                        TextInput::make('whatsapp_message')->label('Mensagem padrão do WhatsApp')->maxLength(300),
                    ]),
                Section::make('Endereço e horários')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('address')->label('Endereço')->required(),
                        TextInput::make('district')->label('Bairro'),
                        TextInput::make('city')->label('Cidade/UF'),
                        TextInput::make('postal_code')->label('CEP')->mask('99999-999'),
                        TextInput::make('hours')->label('Horário de atendimento')->columnSpanFull(),
                        TextInput::make('maps_query')->label('Endereço para o mapa (Google Maps)')->columnSpanFull(),
                        TextInput::make('cnpj')->label('CNPJ')->mask('99.999.999/9999-99'),
                    ]),
                ]),
                Section::make('Rastreamento e anúncios')
                    ->description('Carregados somente para visitantes que aceitam cookies (LGPD). Deixe em branco para desativar.')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('meta_pixel_id')->label('Meta Pixel ID')->placeholder('123456789012345')->regex('/^\d*$/'),
                            TextInput::make('ga4_id')->label('Google Analytics 4 (ID de métricas)')->placeholder('G-XXXXXXXXXX')->regex('/^(G-[A-Z0-9]+)?$/'),
                            TextInput::make('google_ads_id')->label('Google Ads (ID da conta)')->placeholder('AW-123456789')->regex('/^(AW-\d+)?$/'),
                            TextInput::make('google_ads_conversion_label')->label('Google Ads — rótulo de conversão (lead)')->placeholder('AbCdEfGhIj'),
                        ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Salvar alterações')->submit('save'),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::put(collect($data)->map(fn ($v) => $v === null ? '' : $v)->all());
        Cache::forget('sitemap.xml');

        Notification::make()->success()->title('Configurações salvas')->send();
    }
}
