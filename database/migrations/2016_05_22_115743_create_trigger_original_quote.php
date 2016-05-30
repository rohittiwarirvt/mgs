<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerOriginalQuote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER tr_User_Qrigina_Quote AFTER INSERT ON `quotes` FOR EACH ROW
        BEGIN
         INSERT INTO original_quotes (`user_id`, `user_information`, `appointment_date`, `end_date`,`quote_price`, `status_id`, `expire_date`, `quote_source_id`, `created_by`) VALUES (NEW.user_id, NEW.user_information, NEW.appointment_date, NEW.end_date, NEW.quote_price, NEW.status_id, NEW.expire_date,  NEW.quote_source_id, NEW.created_by);
        END
        ');

         DB::unprepared('
        CREATE TRIGGER tr_User_Qrigina_Shopping_Cart_Items AFTER INSERT ON `shopping_cart_items` FOR EACH ROW
        BEGIN
         INSERT INTO original_shopping_cart_items (`product_id`, `quote_id`, `attribute_id`, `option_id`, `option_value`, `attribute_value`, `product_value`, `quantity`, `unit`, `discount`, `created_at`, `updated_at`) VALUES (NEW.product_id, NEW.quote_id, NEW.attribute_id, NEW.option_id, NEW.option_value, NEW.attribute_value, NEW.product_value,  NEW.quantity, NEW.unit, NEW.discount, NEW.created_at, NEW.updated_at);
        END
        ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          DB::unprepared('DROP TRIGGER `tr_User_Qrigina_Quote`');
          DB::unprepared('DROP TRIGGER `tr_User_Qrigina_Shopping_Cart_Items`');
      }
}
