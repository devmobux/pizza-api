<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Cleaning...');

        // Before start creating tables drop all tables if exists
        $this->dropIfExists();

        $this->info('Tables start creating...');

        // Create the ingredients table
        Schema::create('ingredients', function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create the pizzas table
        Schema::create('pizzas', function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->float('price');
            $table->string('image_url');
            $table->timestamps();
        });

        // Create the pizza_ingredient table
        Schema::create('pizza_ingredient', function ($table) {
            $table->id();
            $table->unsignedBigInteger('pizza_id')->unsigned();
            $table->unsignedBigInteger('ingredient_id')->unsigned();
            $table->foreign('ingredient_id')
                  ->references('id')
                  ->on('ingredients');
            $table->foreign('pizza_id')
                  ->references('id')
                  ->on('pizzas');
            $table->timestamps();
        });

        $this->info('Tables created successfully!');

        return CommandAlias::SUCCESS;

    }

    /**
     * Drop all tables if exists
     *
     * @return void
     */
    private function dropIfExists(): void
    {

        Schema::dropIfExists('pizza_ingredient');
        Schema::dropIfExists('pizzas');
        Schema::dropIfExists('ingredients');

    }
}
