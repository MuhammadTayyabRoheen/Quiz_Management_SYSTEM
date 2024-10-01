<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiLogsTable extends Migration
{
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->json('request_data');    // JSON for the request data
            $table->json('response_data');   // JSON for the response data
            $table->integer('status_code');  // Status code for the API request
            $table->string('endpoint');      // The endpoint being called
            $table->string('ip_address');    // The IP address of the request
            $table->timestamps();            // Created at & Updated at
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
}
