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

        // Create the user table
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('last_name');
            $table->string('first_name');
            $table->enum('gender', ['M', 'F']);
            $table->string('phone');
            $table->string('image_profile')->nullable();
            $table->enum('role', ['admin', 'transportation_agent']);
            $table->timestamps();
        });

        // Create the transportation_agent table
        Schema::create('transportation_agents', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                   ->references('id')
                   ->on('users');
            $table->timestamps();
        });

        // Create the location table
        Schema::create('locations', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create the provider table
        Schema::create('providers', function ($table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->enum('gender', ['M', 'F']);
            $table->string('phone');
            $table->string('image_profile')->nullable();
            $table->timestamps();
        });

        // Create the failure_reason table
        Schema::create('failure_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->timestamps();
        });

        // Create the product table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->timestamps();
        });

        // Create the convoy table
        Schema::create('convoys', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedBigInteger('transportation_agent_id');
            $table->foreign('transportation_agent_id')
                  ->references('id')
                  ->on('transportation_agents')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        // Create the convoy_point table
        Schema::create('convoy_points', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sale', 'purchase']);
            $table->boolean('status')->default(false);
            $table->dateTime('arrival_date')->nullable();
            $table->unsignedBigInteger('convoy_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('failure_reason_id')->nullable();
            $table->foreign('convoy_id')
                  ->references('id')
                  ->on('convoys')
                  ->onDelete('cascade');
            $table->foreign('location_id')
                  ->references('id')
                  ->on('locations')
                  ->onDelete('cascade');
            $table->foreign('provider_id')
                   ->references('id')
                   ->on('providers')
                   ->onDelete('cascade');
            $table->foreign('failure_reason_id')
                  ->references('id')
                  ->on('failure_reasons')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        // Create the convoy_product table
        Schema::create('convoy_products', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 8, 2);
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('weight_unit')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('quantity_suffix')->nullable();
            $table->unsignedBigInteger('convoy_point_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            $table->foreign('convoy_point_id')
                  ->references('id')
                  ->on('convoy_points')
                  ->onDelete('cascade');
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
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

        Schema::dropIfExists('convoy_products');
        Schema::dropIfExists('convoy_points');
        Schema::dropIfExists('convoys');
        Schema::dropIfExists('failure_reasons');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('transportation_agents');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('users');

    }
}
