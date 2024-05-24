<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Detail;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    use WithExport;

    public array $email = [];

    protected array $rules = [
        'email.*' => ['required', 'email'],
    ];

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),

            Detail::make()
                ->view('components.detail')
                ->params(['name' => 'Luan'])
                ->showCollapseIcon(),
        ];
    }

    protected function queryString()
    {
        return $this->powerGridQueryString('user');
    }

    public function datasource(): Builder
    {
        return User::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('created_at')
            ->add('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                 ->sortable(),

            Column::make('Full Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->editOnClick(true)
                ->searchable(),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make('UPDATED AT', 'updated_at_formatted', 'updated_at')
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::inputText('email'),
            Filter::datetimepicker('created_at', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(User $row): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('btn btn-primary')
                ->route('user.edit', ['user' => 'id']),

            Button::make('destroy', 'Delete')
                ->class('btn btn-danger')
                ->route('user.destroy', ['user' => 'id'])
                ->method('delete')
         ];
    }

    public function actionRules(User $row): array
    {
        return [
             Rule::button('edit')
                 ->when(fn ($row) => $row->id === 1)
                 ->hide(),
         ];
    }

    public function onUpdatedEditable($id, $field, $value): void
    {
        User::query()->find($id)->update([
            $field => $value,
        ]);
    }
}
