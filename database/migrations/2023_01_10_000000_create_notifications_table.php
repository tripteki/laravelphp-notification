<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tripteki\Helpers\Contracts\AuthModelContract;

class CreateNotificationsTable extends Migration
{
    /**
     * @var string
     */
    protected $keytype;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->keytype = app(AuthModelContract::class)->getKeyType();
    }

    /**
     * @return void
     */
    public function up()
    {
        $keytype = $this->keytype;

        Schema::create("notifications", function (Blueprint $table) use ($keytype) {

            $table->uuid("id");
            $table->string("type");

            if ($keytype == "int") $table->morphs("notifiable");
            else if ($keytype == "string") $table->uuidMorphs("notifiable");

            $table->text("data");

            $table->timestamp("read_at")->nullable(true);
            $table->timestamp("created_at")->nullable(true);
            $table->timestamp("updated_at")->nullable(true);

            $table->primary("id");
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("notifications");
    }
};
