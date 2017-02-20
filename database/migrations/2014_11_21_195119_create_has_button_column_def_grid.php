<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateHasButtonColumnDefGrid extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            if (!Schema::hasColumn('def_grid', 'has_button_column')) {
                Schema::table('def_grid', function (Blueprint $table) {
                    //
                    $table->boolean('has_button_column')->default(false);
                });
            }
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('def_grid', function (Blueprint $table) {
                $table->dropColumn('has_button_column');
            });
        }

    }
