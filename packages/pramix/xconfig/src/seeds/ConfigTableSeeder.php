<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $config = new \Pramix\XConfig\Models\XConfig();
        $config->name = 'CONFIG_TYPES';
        $config->display_name = 'Config Type';
        $config->config_type = 'DD';
        $config->value = 'TX';
        $config->options_value = '"{\"TX\":\"Text\",\"DD\":\"Drop-down\"}"';
        $config->category_id = 0;
        $config->status = 1;
        $config->save();
    }
}
