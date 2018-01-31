<?php

//    https://github.com/MaxKorlaar/Pxl

//   https://github.com/muratbsts/stories


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinedEntitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

    Schema::create('users', function (Blueprint $table) {
               $table->increments('id');
               $table->uuid('uuid');
               $table->string('name');
               $table->string('email')->unique();
               $table->string('password');
               $table->float('quota')->nullable()->default(null);
               $table->float('size')->nullable()->default(null);
               $table->rememberToken();
               $table->timestamps();
           });

           Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


    Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->default((int) self::DEFAULT_CATEGORY_ID);
            $table->string('title')->nullable();
            $table->text('md_content')->nullable();
            $table->text('html_content')->nullable();
            $table->string('publication_status')->default('draft');
            $table->datetime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->nullable();
            $table->text('comment');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tag_post', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned();
            $table->integer('post_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
//https://github.com/arvernester/file-sharing
        Schema::create('files', function (Blueprint $table) {
                   $table->increments('id');
                   $table->unsignedInteger('user_id')->nullable();
                   $table->string('uuid');
                   $table->string('label', 250);
                   $table->string('path');
                   $table->string('password')->nullable()->default(null);
                   $table->string('plain_password', 400)->nullable()->default(null);
                   $table->boolean('is_private')->default(false);
                   $table->dateTime('expired_at')->nullable()->default(null);
                   $table->timestamps();
               });

/*
Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->string('file_hashed');
            $table->string('file_path');
            $table->bigInteger('file_size')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->ipAddress('visitor');
            $table->timestamps();
        });

*/


               Schema::create('file_downloads', function (Blueprint $table) {
                 $table->increments('id');
                 $table->unsignedInteger('file_id');
                 $table->unsignedInteger('user_id')->nullable()->default(null);
                 $table->timestamps();
             });

             Schema::create('file_reports', function (Blueprint $table) {
                  $table->increments('id');
                  $table->uuid('uuid')->unique();
                  $table->unsignedInteger('file_id');
                  $table->unsignedInteger('user_id')->nullable()->default(null);
                  $table->string('name', 50)->nullable()->default(null);
                  $table->string('email', 100)->nullable()->default(null);
                  $table->enum('status', [
                      'pending',
                      'processing',
                      'deleted',
                      'rejected',
                  ])->default('pending');
                  $table->text('message');
                  $table->timestamps();
              });

              Schema::create('roles', function (Blueprint $table) {
                  $table->increments('id');
                  $table->string('slug')->unique();
                  $table->string('name');
                  $table->text('permissions');
                  $table->timestamps();
              });

              Schema::create('role_users', function (Blueprint $table) {
                  $table->unsignedInteger('user_id');
                  $table->unsignedInteger('role_id');
                  $table->timestamps();
                  $table->unique(['user_id', 'role_id']);
                  $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                  $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
              });

        Schema::create('configs', function (Blueprint $table) {
           $table->increments('id');
           $table->string('name');
           $table->string('alias_name');
           $table->string('value');
           $table->timestamps();
           $table->softDeletes();
       });

      }
 /**
  * Reverse the migrations.
  */
 public function down()
 {
     Schema::drop('tag_post');
     Schema::drop('posts');
     Schema::drop('categories');
     Schema::drop('comments');
     Schema::drop('tags');
     Schema::drop('configs');
 }

}
