<?php
require __DIR__.'/../base/init.php';

use Illuminate\Database\Capsule\Manager as DB;

function migrate()
{
    DB::schema()->dropIfExists('category');
    DB::schema()->create('category', function ($table) {
        $table->increments('id');
        $table->string('name', 50)->nullable(false);
        $table->string('description')->nullable(true);
        $table->integer('deleted')->nullable(false)->default(0);
    });
    echo "Таблица category создана\n";

    DB::schema()->dropIfExists('product');
    DB::schema()->create('product', function ($table) {
        $table->increments('id');
        $table->integer('category_id')->unsigned()->nullable(false)->default(0);
        $table->foreign('category_id')->references('id')->on('category');
        $table->string('name', 50)->nullable(false);
        $table->string('description')->nullable(true);
        $table->decimal('price', 19, 2)->nullable(false)->default(0.00);
        $table->string('image')->nullable(true);
        $table->timestamps();
        $table->integer('deleted')->nullable(false)->default(0);
    });
    echo "Таблица product создана\n";
}

function rollback()
{
    DB::schema()->dropIfExists('product');
    DB::schema()->dropIfExists('category');
}

try {
    $args = !empty($argv[1]) ? $argv[1] : null;
    switch ($args) {
        case '':
        case 'migrate':
            migrate();
            echo "Миграция прошла успешно";
            break;
        case 'rollback':
            rollback();
            echo "Откат прошел успешно";
            break;
    }
} catch (\Exception $exception) {
    echo "При миграции возникла ошибка. {$exception->getMessage()}";
}