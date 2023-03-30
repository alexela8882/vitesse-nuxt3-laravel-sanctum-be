<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
          $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

          return new LengthAwarePaginator(
            $this->forPage($page, $perPage),
            $total ?: $this->count(),
            $perPage,
            $page,
            [
              'path' => LengthAwarePaginator::resolveCurrentPath(),
              'pageName' => $pageName,
            ]
          );
        });

        Collection::macro('sortByDate', function (string $column = 'created_at', bool $descending = true) {
          /* @var $this Collection */
          return $this->sortBy(function ($datum) use ($column) {
            return strtotime(((object)$datum)->$column);
          }, SORT_REGULAR, $descending);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
