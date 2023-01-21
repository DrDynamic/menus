<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *create_ingre
     * @return void
     */
    public function boot()
    {
        Collection::macro('mutations',
            function (Model  $model,
                      string $relation,
                      string $field = 'id') {
                $update = $this->whereNotNull($field);

                return [
                    'create' => $this->whereNull($field),
                    'update' => $this->whereNotNull($field),
                    'delete' => $model->$relation->whereNotIn($field, $update->pluck('id'))
                ];
            });
    }
}
