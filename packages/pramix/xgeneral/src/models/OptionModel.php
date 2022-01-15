<?php

namespace Pramix\XGeneral\Models;

use Illuminate\Database\Eloquent\Model;

class OptionModel extends Model
{
    public static function generateCode($prefix, $length, $last_record)
    {

        if ($last_record == NULL) {
            $last_record_id = 0;
        } else {

            $last_record = preg_replace("/[^0-9\.]/", '', $last_record);

            $last_record_id = (int)$last_record;
        }

        $order_no = $prefix.'-' . (str_pad($last_record_id + 1, $length, "0", STR_PAD_LEFT));

        return $order_no;
    }
}
