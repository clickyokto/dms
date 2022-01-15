<?php

use Illuminate\Database\Seeder;

class RoleHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_has_permissions')->delete();
        
        \DB::table('role_has_permissions')->insert(array (
            0 => 
            array (
                'permission_id' => 1,
                'role_id' => 1,
            ),
            1 => 
            array (
                'permission_id' => 1,
                'role_id' => 2,
            ),
            2 => 
            array (
                'permission_id' => 1,
                'role_id' => 4,
            ),
            3 => 
            array (
                'permission_id' => 1,
                'role_id' => 6,
            ),
            4 => 
            array (
                'permission_id' => 1,
                'role_id' => 7,
            ),
            5 => 
            array (
                'permission_id' => 2,
                'role_id' => 3,
            ),
            6 => 
            array (
                'permission_id' => 3,
                'role_id' => 1,
            ),
            7 => 
            array (
                'permission_id' => 3,
                'role_id' => 2,
            ),
            8 => 
            array (
                'permission_id' => 3,
                'role_id' => 4,
            ),
            9 => 
            array (
                'permission_id' => 3,
                'role_id' => 6,
            ),
            10 => 
            array (
                'permission_id' => 3,
                'role_id' => 7,
            ),
            11 => 
            array (
                'permission_id' => 4,
                'role_id' => 1,
            ),
            12 => 
            array (
                'permission_id' => 4,
                'role_id' => 2,
            ),
            13 => 
            array (
                'permission_id' => 4,
                'role_id' => 4,
            ),
            14 => 
            array (
                'permission_id' => 4,
                'role_id' => 6,
            ),
            15 => 
            array (
                'permission_id' => 4,
                'role_id' => 7,
            ),
            16 => 
            array (
                'permission_id' => 7,
                'role_id' => 1,
            ),
            17 => 
            array (
                'permission_id' => 7,
                'role_id' => 2,
            ),
            18 => 
            array (
                'permission_id' => 7,
                'role_id' => 4,
            ),
            19 => 
            array (
                'permission_id' => 7,
                'role_id' => 5,
            ),
            20 => 
            array (
                'permission_id' => 7,
                'role_id' => 6,
            ),
            21 => 
            array (
                'permission_id' => 7,
                'role_id' => 7,
            ),
            22 => 
            array (
                'permission_id' => 8,
                'role_id' => 1,
            ),
            23 => 
            array (
                'permission_id' => 8,
                'role_id' => 2,
            ),
            24 => 
            array (
                'permission_id' => 8,
                'role_id' => 4,
            ),
            25 => 
            array (
                'permission_id' => 8,
                'role_id' => 5,
            ),
            26 => 
            array (
                'permission_id' => 8,
                'role_id' => 6,
            ),
            27 => 
            array (
                'permission_id' => 8,
                'role_id' => 7,
            ),
            28 => 
            array (
                'permission_id' => 9,
                'role_id' => 1,
            ),
            29 => 
            array (
                'permission_id' => 9,
                'role_id' => 2,
            ),
            30 => 
            array (
                'permission_id' => 9,
                'role_id' => 4,
            ),
            31 => 
            array (
                'permission_id' => 9,
                'role_id' => 5,
            ),
            32 => 
            array (
                'permission_id' => 9,
                'role_id' => 6,
            ),
            33 => 
            array (
                'permission_id' => 9,
                'role_id' => 7,
            ),
            34 => 
            array (
                'permission_id' => 12,
                'role_id' => 1,
            ),
            35 => 
            array (
                'permission_id' => 12,
                'role_id' => 2,
            ),
            36 => 
            array (
                'permission_id' => 12,
                'role_id' => 4,
            ),
            37 => 
            array (
                'permission_id' => 12,
                'role_id' => 5,
            ),
            38 => 
            array (
                'permission_id' => 12,
                'role_id' => 6,
            ),
            39 => 
            array (
                'permission_id' => 12,
                'role_id' => 7,
            ),
            40 => 
            array (
                'permission_id' => 13,
                'role_id' => 1,
            ),
            41 => 
            array (
                'permission_id' => 13,
                'role_id' => 2,
            ),
            42 => 
            array (
                'permission_id' => 13,
                'role_id' => 4,
            ),
            43 => 
            array (
                'permission_id' => 13,
                'role_id' => 5,
            ),
            44 => 
            array (
                'permission_id' => 13,
                'role_id' => 6,
            ),
            45 => 
            array (
                'permission_id' => 13,
                'role_id' => 7,
            ),
            46 => 
            array (
                'permission_id' => 14,
                'role_id' => 1,
            ),
            47 => 
            array (
                'permission_id' => 14,
                'role_id' => 2,
            ),
            48 => 
            array (
                'permission_id' => 14,
                'role_id' => 4,
            ),
            49 => 
            array (
                'permission_id' => 14,
                'role_id' => 5,
            ),
            50 => 
            array (
                'permission_id' => 14,
                'role_id' => 6,
            ),
            51 => 
            array (
                'permission_id' => 14,
                'role_id' => 7,
            ),
            52 => 
            array (
                'permission_id' => 15,
                'role_id' => 1,
            ),
            53 => 
            array (
                'permission_id' => 15,
                'role_id' => 2,
            ),
            54 => 
            array (
                'permission_id' => 15,
                'role_id' => 4,
            ),
            55 => 
            array (
                'permission_id' => 15,
                'role_id' => 5,
            ),
            56 => 
            array (
                'permission_id' => 15,
                'role_id' => 6,
            ),
            57 => 
            array (
                'permission_id' => 15,
                'role_id' => 7,
            ),
            58 => 
            array (
                'permission_id' => 20,
                'role_id' => 1,
            ),
            59 => 
            array (
                'permission_id' => 20,
                'role_id' => 2,
            ),
            60 => 
            array (
                'permission_id' => 20,
                'role_id' => 4,
            ),
            61 => 
            array (
                'permission_id' => 20,
                'role_id' => 5,
            ),
            62 => 
            array (
                'permission_id' => 20,
                'role_id' => 6,
            ),
            63 => 
            array (
                'permission_id' => 20,
                'role_id' => 7,
            ),
            64 => 
            array (
                'permission_id' => 21,
                'role_id' => 1,
            ),
            65 => 
            array (
                'permission_id' => 21,
                'role_id' => 2,
            ),
            66 => 
            array (
                'permission_id' => 21,
                'role_id' => 4,
            ),
            67 => 
            array (
                'permission_id' => 21,
                'role_id' => 5,
            ),
            68 => 
            array (
                'permission_id' => 21,
                'role_id' => 6,
            ),
            69 => 
            array (
                'permission_id' => 21,
                'role_id' => 7,
            ),
            70 => 
            array (
                'permission_id' => 22,
                'role_id' => 1,
            ),
            71 => 
            array (
                'permission_id' => 22,
                'role_id' => 2,
            ),
            72 => 
            array (
                'permission_id' => 22,
                'role_id' => 4,
            ),
            73 => 
            array (
                'permission_id' => 22,
                'role_id' => 5,
            ),
            74 => 
            array (
                'permission_id' => 22,
                'role_id' => 6,
            ),
            75 => 
            array (
                'permission_id' => 22,
                'role_id' => 7,
            ),
            76 => 
            array (
                'permission_id' => 23,
                'role_id' => 1,
            ),
            77 => 
            array (
                'permission_id' => 23,
                'role_id' => 2,
            ),
            78 => 
            array (
                'permission_id' => 23,
                'role_id' => 4,
            ),
            79 => 
            array (
                'permission_id' => 23,
                'role_id' => 6,
            ),
            80 => 
            array (
                'permission_id' => 23,
                'role_id' => 7,
            ),
            81 => 
            array (
                'permission_id' => 25,
                'role_id' => 1,
            ),
            82 => 
            array (
                'permission_id' => 25,
                'role_id' => 2,
            ),
            83 => 
            array (
                'permission_id' => 25,
                'role_id' => 4,
            ),
            84 => 
            array (
                'permission_id' => 25,
                'role_id' => 5,
            ),
            85 => 
            array (
                'permission_id' => 25,
                'role_id' => 6,
            ),
            86 => 
            array (
                'permission_id' => 25,
                'role_id' => 7,
            ),
            87 => 
            array (
                'permission_id' => 26,
                'role_id' => 1,
            ),
            88 => 
            array (
                'permission_id' => 26,
                'role_id' => 2,
            ),
            89 => 
            array (
                'permission_id' => 26,
                'role_id' => 4,
            ),
            90 => 
            array (
                'permission_id' => 26,
                'role_id' => 5,
            ),
            91 => 
            array (
                'permission_id' => 26,
                'role_id' => 6,
            ),
            92 => 
            array (
                'permission_id' => 26,
                'role_id' => 7,
            ),
            93 => 
            array (
                'permission_id' => 27,
                'role_id' => 1,
            ),
            94 => 
            array (
                'permission_id' => 27,
                'role_id' => 2,
            ),
            95 => 
            array (
                'permission_id' => 27,
                'role_id' => 4,
            ),
            96 => 
            array (
                'permission_id' => 27,
                'role_id' => 5,
            ),
            97 => 
            array (
                'permission_id' => 27,
                'role_id' => 6,
            ),
            98 => 
            array (
                'permission_id' => 27,
                'role_id' => 7,
            ),
            99 => 
            array (
                'permission_id' => 33,
                'role_id' => 1,
            ),
            100 => 
            array (
                'permission_id' => 33,
                'role_id' => 2,
            ),
            101 => 
            array (
                'permission_id' => 33,
                'role_id' => 4,
            ),
            102 => 
            array (
                'permission_id' => 33,
                'role_id' => 6,
            ),
            103 => 
            array (
                'permission_id' => 33,
                'role_id' => 7,
            ),
            104 => 
            array (
                'permission_id' => 34,
                'role_id' => 1,
            ),
            105 => 
            array (
                'permission_id' => 34,
                'role_id' => 2,
            ),
            106 => 
            array (
                'permission_id' => 34,
                'role_id' => 4,
            ),
            107 => 
            array (
                'permission_id' => 34,
                'role_id' => 6,
            ),
            108 => 
            array (
                'permission_id' => 34,
                'role_id' => 7,
            ),
            109 => 
            array (
                'permission_id' => 35,
                'role_id' => 1,
            ),
            110 => 
            array (
                'permission_id' => 35,
                'role_id' => 2,
            ),
            111 => 
            array (
                'permission_id' => 35,
                'role_id' => 4,
            ),
            112 => 
            array (
                'permission_id' => 35,
                'role_id' => 6,
            ),
            113 => 
            array (
                'permission_id' => 35,
                'role_id' => 7,
            ),
            114 => 
            array (
                'permission_id' => 37,
                'role_id' => 1,
            ),
            115 => 
            array (
                'permission_id' => 37,
                'role_id' => 2,
            ),
            116 => 
            array (
                'permission_id' => 37,
                'role_id' => 4,
            ),
            117 => 
            array (
                'permission_id' => 37,
                'role_id' => 6,
            ),
            118 => 
            array (
                'permission_id' => 37,
                'role_id' => 7,
            ),
            119 => 
            array (
                'permission_id' => 38,
                'role_id' => 1,
            ),
            120 => 
            array (
                'permission_id' => 38,
                'role_id' => 2,
            ),
            121 => 
            array (
                'permission_id' => 38,
                'role_id' => 4,
            ),
            122 => 
            array (
                'permission_id' => 38,
                'role_id' => 6,
            ),
            123 => 
            array (
                'permission_id' => 38,
                'role_id' => 7,
            ),
            124 => 
            array (
                'permission_id' => 39,
                'role_id' => 1,
            ),
            125 => 
            array (
                'permission_id' => 39,
                'role_id' => 2,
            ),
            126 => 
            array (
                'permission_id' => 39,
                'role_id' => 4,
            ),
            127 => 
            array (
                'permission_id' => 39,
                'role_id' => 6,
            ),
            128 => 
            array (
                'permission_id' => 39,
                'role_id' => 7,
            ),
            129 => 
            array (
                'permission_id' => 45,
                'role_id' => 1,
            ),
            130 => 
            array (
                'permission_id' => 45,
                'role_id' => 6,
            ),
            131 => 
            array (
                'permission_id' => 45,
                'role_id' => 7,
            ),
            132 => 
            array (
                'permission_id' => 46,
                'role_id' => 1,
            ),
            133 => 
            array (
                'permission_id' => 46,
                'role_id' => 2,
            ),
            134 => 
            array (
                'permission_id' => 46,
                'role_id' => 4,
            ),
            135 => 
            array (
                'permission_id' => 46,
                'role_id' => 6,
            ),
            136 => 
            array (
                'permission_id' => 46,
                'role_id' => 7,
            ),
            137 => 
            array (
                'permission_id' => 47,
                'role_id' => 1,
            ),
            138 => 
            array (
                'permission_id' => 47,
                'role_id' => 2,
            ),
            139 => 
            array (
                'permission_id' => 47,
                'role_id' => 4,
            ),
            140 => 
            array (
                'permission_id' => 47,
                'role_id' => 6,
            ),
            141 => 
            array (
                'permission_id' => 47,
                'role_id' => 7,
            ),
            142 => 
            array (
                'permission_id' => 48,
                'role_id' => 1,
            ),
            143 => 
            array (
                'permission_id' => 48,
                'role_id' => 6,
            ),
            144 => 
            array (
                'permission_id' => 48,
                'role_id' => 7,
            ),
            145 => 
            array (
                'permission_id' => 49,
                'role_id' => 1,
            ),
            146 => 
            array (
                'permission_id' => 49,
                'role_id' => 6,
            ),
            147 => 
            array (
                'permission_id' => 49,
                'role_id' => 7,
            ),
            148 => 
            array (
                'permission_id' => 50,
                'role_id' => 1,
            ),
            149 => 
            array (
                'permission_id' => 50,
                'role_id' => 6,
            ),
            150 => 
            array (
                'permission_id' => 50,
                'role_id' => 7,
            ),
            151 => 
            array (
                'permission_id' => 51,
                'role_id' => 1,
            ),
            152 => 
            array (
                'permission_id' => 51,
                'role_id' => 2,
            ),
            153 => 
            array (
                'permission_id' => 51,
                'role_id' => 4,
            ),
            154 => 
            array (
                'permission_id' => 51,
                'role_id' => 6,
            ),
            155 => 
            array (
                'permission_id' => 51,
                'role_id' => 7,
            ),
            156 => 
            array (
                'permission_id' => 52,
                'role_id' => 1,
            ),
            157 => 
            array (
                'permission_id' => 52,
                'role_id' => 2,
            ),
            158 => 
            array (
                'permission_id' => 52,
                'role_id' => 4,
            ),
            159 => 
            array (
                'permission_id' => 52,
                'role_id' => 6,
            ),
            160 => 
            array (
                'permission_id' => 52,
                'role_id' => 7,
            ),
            161 => 
            array (
                'permission_id' => 53,
                'role_id' => 1,
            ),
            162 => 
            array (
                'permission_id' => 53,
                'role_id' => 2,
            ),
            163 => 
            array (
                'permission_id' => 53,
                'role_id' => 4,
            ),
            164 => 
            array (
                'permission_id' => 53,
                'role_id' => 6,
            ),
            165 => 
            array (
                'permission_id' => 53,
                'role_id' => 7,
            ),
            166 => 
            array (
                'permission_id' => 55,
                'role_id' => 1,
            ),
            167 => 
            array (
                'permission_id' => 55,
                'role_id' => 2,
            ),
            168 => 
            array (
                'permission_id' => 55,
                'role_id' => 4,
            ),
            169 => 
            array (
                'permission_id' => 55,
                'role_id' => 6,
            ),
            170 => 
            array (
                'permission_id' => 55,
                'role_id' => 7,
            ),
            171 => 
            array (
                'permission_id' => 56,
                'role_id' => 1,
            ),
            172 => 
            array (
                'permission_id' => 56,
                'role_id' => 2,
            ),
            173 => 
            array (
                'permission_id' => 56,
                'role_id' => 4,
            ),
            174 => 
            array (
                'permission_id' => 56,
                'role_id' => 6,
            ),
            175 => 
            array (
                'permission_id' => 56,
                'role_id' => 7,
            ),
            176 => 
            array (
                'permission_id' => 57,
                'role_id' => 1,
            ),
            177 => 
            array (
                'permission_id' => 57,
                'role_id' => 2,
            ),
            178 => 
            array (
                'permission_id' => 57,
                'role_id' => 4,
            ),
            179 => 
            array (
                'permission_id' => 57,
                'role_id' => 6,
            ),
            180 => 
            array (
                'permission_id' => 57,
                'role_id' => 7,
            ),
            181 => 
            array (
                'permission_id' => 58,
                'role_id' => 1,
            ),
            182 => 
            array (
                'permission_id' => 58,
                'role_id' => 2,
            ),
            183 => 
            array (
                'permission_id' => 58,
                'role_id' => 4,
            ),
            184 => 
            array (
                'permission_id' => 58,
                'role_id' => 6,
            ),
            185 => 
            array (
                'permission_id' => 58,
                'role_id' => 7,
            ),
            186 => 
            array (
                'permission_id' => 60,
                'role_id' => 1,
            ),
            187 => 
            array (
                'permission_id' => 60,
                'role_id' => 2,
            ),
            188 => 
            array (
                'permission_id' => 60,
                'role_id' => 4,
            ),
            189 => 
            array (
                'permission_id' => 60,
                'role_id' => 6,
            ),
            190 => 
            array (
                'permission_id' => 60,
                'role_id' => 7,
            ),
            191 => 
            array (
                'permission_id' => 61,
                'role_id' => 1,
            ),
            192 => 
            array (
                'permission_id' => 61,
                'role_id' => 6,
            ),
            193 => 
            array (
                'permission_id' => 61,
                'role_id' => 7,
            ),
            194 => 
            array (
                'permission_id' => 62,
                'role_id' => 1,
            ),
            195 => 
            array (
                'permission_id' => 62,
                'role_id' => 6,
            ),
            196 => 
            array (
                'permission_id' => 62,
                'role_id' => 7,
            ),
            197 => 
            array (
                'permission_id' => 64,
                'role_id' => 1,
            ),
            198 => 
            array (
                'permission_id' => 64,
                'role_id' => 6,
            ),
            199 => 
            array (
                'permission_id' => 64,
                'role_id' => 7,
            ),
            200 => 
            array (
                'permission_id' => 65,
                'role_id' => 1,
            ),
            201 => 
            array (
                'permission_id' => 65,
                'role_id' => 6,
            ),
            202 => 
            array (
                'permission_id' => 65,
                'role_id' => 7,
            ),
            203 => 
            array (
                'permission_id' => 67,
                'role_id' => 1,
            ),
            204 => 
            array (
                'permission_id' => 67,
                'role_id' => 2,
            ),
            205 => 
            array (
                'permission_id' => 67,
                'role_id' => 4,
            ),
            206 => 
            array (
                'permission_id' => 67,
                'role_id' => 6,
            ),
            207 => 
            array (
                'permission_id' => 67,
                'role_id' => 7,
            ),
            208 => 
            array (
                'permission_id' => 68,
                'role_id' => 1,
            ),
            209 => 
            array (
                'permission_id' => 68,
                'role_id' => 4,
            ),
            210 => 
            array (
                'permission_id' => 68,
                'role_id' => 6,
            ),
            211 => 
            array (
                'permission_id' => 68,
                'role_id' => 7,
            ),
            212 => 
            array (
                'permission_id' => 70,
                'role_id' => 1,
            ),
            213 => 
            array (
                'permission_id' => 70,
                'role_id' => 2,
            ),
            214 => 
            array (
                'permission_id' => 70,
                'role_id' => 4,
            ),
            215 => 
            array (
                'permission_id' => 70,
                'role_id' => 6,
            ),
            216 => 
            array (
                'permission_id' => 70,
                'role_id' => 7,
            ),
            217 => 
            array (
                'permission_id' => 71,
                'role_id' => 1,
            ),
            218 => 
            array (
                'permission_id' => 71,
                'role_id' => 2,
            ),
            219 => 
            array (
                'permission_id' => 71,
                'role_id' => 4,
            ),
            220 => 
            array (
                'permission_id' => 71,
                'role_id' => 6,
            ),
            221 => 
            array (
                'permission_id' => 71,
                'role_id' => 7,
            ),
            222 => 
            array (
                'permission_id' => 79,
                'role_id' => 1,
            ),
            223 => 
            array (
                'permission_id' => 79,
                'role_id' => 2,
            ),
            224 => 
            array (
                'permission_id' => 79,
                'role_id' => 4,
            ),
            225 => 
            array (
                'permission_id' => 79,
                'role_id' => 6,
            ),
            226 => 
            array (
                'permission_id' => 79,
                'role_id' => 7,
            ),
            227 => 
            array (
                'permission_id' => 80,
                'role_id' => 1,
            ),
            228 => 
            array (
                'permission_id' => 80,
                'role_id' => 2,
            ),
            229 => 
            array (
                'permission_id' => 80,
                'role_id' => 4,
            ),
            230 => 
            array (
                'permission_id' => 80,
                'role_id' => 6,
            ),
            231 => 
            array (
                'permission_id' => 80,
                'role_id' => 7,
            ),
            232 => 
            array (
                'permission_id' => 81,
                'role_id' => 1,
            ),
            233 => 
            array (
                'permission_id' => 81,
                'role_id' => 2,
            ),
            234 => 
            array (
                'permission_id' => 81,
                'role_id' => 4,
            ),
            235 => 
            array (
                'permission_id' => 81,
                'role_id' => 6,
            ),
            236 => 
            array (
                'permission_id' => 81,
                'role_id' => 7,
            ),
            237 => 
            array (
                'permission_id' => 100,
                'role_id' => 1,
            ),
            238 => 
            array (
                'permission_id' => 100,
                'role_id' => 2,
            ),
            239 => 
            array (
                'permission_id' => 100,
                'role_id' => 4,
            ),
            240 => 
            array (
                'permission_id' => 100,
                'role_id' => 5,
            ),
            241 => 
            array (
                'permission_id' => 100,
                'role_id' => 6,
            ),
            242 => 
            array (
                'permission_id' => 100,
                'role_id' => 7,
            ),
            243 => 
            array (
                'permission_id' => 101,
                'role_id' => 1,
            ),
            244 => 
            array (
                'permission_id' => 101,
                'role_id' => 2,
            ),
            245 => 
            array (
                'permission_id' => 101,
                'role_id' => 4,
            ),
            246 => 
            array (
                'permission_id' => 101,
                'role_id' => 5,
            ),
            247 => 
            array (
                'permission_id' => 101,
                'role_id' => 6,
            ),
            248 => 
            array (
                'permission_id' => 101,
                'role_id' => 7,
            ),
            249 => 
            array (
                'permission_id' => 102,
                'role_id' => 1,
            ),
            250 => 
            array (
                'permission_id' => 102,
                'role_id' => 2,
            ),
            251 => 
            array (
                'permission_id' => 102,
                'role_id' => 4,
            ),
            252 => 
            array (
                'permission_id' => 102,
                'role_id' => 5,
            ),
            253 => 
            array (
                'permission_id' => 102,
                'role_id' => 6,
            ),
            254 => 
            array (
                'permission_id' => 102,
                'role_id' => 7,
            ),
            255 => 
            array (
                'permission_id' => 103,
                'role_id' => 1,
            ),
            256 => 
            array (
                'permission_id' => 103,
                'role_id' => 2,
            ),
            257 => 
            array (
                'permission_id' => 103,
                'role_id' => 4,
            ),
            258 => 
            array (
                'permission_id' => 103,
                'role_id' => 5,
            ),
            259 => 
            array (
                'permission_id' => 103,
                'role_id' => 6,
            ),
            260 => 
            array (
                'permission_id' => 103,
                'role_id' => 7,
            ),
            261 => 
            array (
                'permission_id' => 104,
                'role_id' => 1,
            ),
            262 => 
            array (
                'permission_id' => 104,
                'role_id' => 2,
            ),
            263 => 
            array (
                'permission_id' => 104,
                'role_id' => 4,
            ),
            264 => 
            array (
                'permission_id' => 104,
                'role_id' => 5,
            ),
            265 => 
            array (
                'permission_id' => 104,
                'role_id' => 6,
            ),
            266 => 
            array (
                'permission_id' => 104,
                'role_id' => 7,
            ),
            267 => 
            array (
                'permission_id' => 105,
                'role_id' => 1,
            ),
            268 => 
            array (
                'permission_id' => 105,
                'role_id' => 2,
            ),
            269 => 
            array (
                'permission_id' => 105,
                'role_id' => 4,
            ),
            270 => 
            array (
                'permission_id' => 105,
                'role_id' => 6,
            ),
            271 => 
            array (
                'permission_id' => 105,
                'role_id' => 7,
            ),
            272 => 
            array (
                'permission_id' => 106,
                'role_id' => 1,
            ),
            273 => 
            array (
                'permission_id' => 106,
                'role_id' => 2,
            ),
            274 => 
            array (
                'permission_id' => 106,
                'role_id' => 4,
            ),
            275 => 
            array (
                'permission_id' => 106,
                'role_id' => 5,
            ),
            276 => 
            array (
                'permission_id' => 106,
                'role_id' => 6,
            ),
            277 => 
            array (
                'permission_id' => 106,
                'role_id' => 7,
            ),
            278 => 
            array (
                'permission_id' => 107,
                'role_id' => 1,
            ),
            279 => 
            array (
                'permission_id' => 107,
                'role_id' => 2,
            ),
            280 => 
            array (
                'permission_id' => 107,
                'role_id' => 4,
            ),
            281 => 
            array (
                'permission_id' => 107,
                'role_id' => 5,
            ),
            282 => 
            array (
                'permission_id' => 107,
                'role_id' => 6,
            ),
            283 => 
            array (
                'permission_id' => 107,
                'role_id' => 7,
            ),
            284 => 
            array (
                'permission_id' => 108,
                'role_id' => 1,
            ),
            285 => 
            array (
                'permission_id' => 108,
                'role_id' => 2,
            ),
            286 => 
            array (
                'permission_id' => 108,
                'role_id' => 4,
            ),
            287 => 
            array (
                'permission_id' => 108,
                'role_id' => 5,
            ),
            288 => 
            array (
                'permission_id' => 108,
                'role_id' => 6,
            ),
            289 => 
            array (
                'permission_id' => 108,
                'role_id' => 7,
            ),
            290 => 
            array (
                'permission_id' => 109,
                'role_id' => 1,
            ),
            291 => 
            array (
                'permission_id' => 109,
                'role_id' => 2,
            ),
            292 => 
            array (
                'permission_id' => 109,
                'role_id' => 4,
            ),
            293 => 
            array (
                'permission_id' => 109,
                'role_id' => 5,
            ),
            294 => 
            array (
                'permission_id' => 109,
                'role_id' => 6,
            ),
            295 => 
            array (
                'permission_id' => 109,
                'role_id' => 7,
            ),
            296 => 
            array (
                'permission_id' => 110,
                'role_id' => 1,
            ),
            297 => 
            array (
                'permission_id' => 110,
                'role_id' => 2,
            ),
            298 => 
            array (
                'permission_id' => 110,
                'role_id' => 4,
            ),
            299 => 
            array (
                'permission_id' => 110,
                'role_id' => 5,
            ),
            300 => 
            array (
                'permission_id' => 110,
                'role_id' => 6,
            ),
            301 => 
            array (
                'permission_id' => 110,
                'role_id' => 7,
            ),
            302 => 
            array (
                'permission_id' => 111,
                'role_id' => 1,
            ),
            303 => 
            array (
                'permission_id' => 111,
                'role_id' => 2,
            ),
            304 => 
            array (
                'permission_id' => 111,
                'role_id' => 4,
            ),
            305 => 
            array (
                'permission_id' => 111,
                'role_id' => 5,
            ),
            306 => 
            array (
                'permission_id' => 111,
                'role_id' => 6,
            ),
            307 => 
            array (
                'permission_id' => 111,
                'role_id' => 7,
            ),
            308 => 
            array (
                'permission_id' => 112,
                'role_id' => 1,
            ),
            309 => 
            array (
                'permission_id' => 112,
                'role_id' => 2,
            ),
            310 => 
            array (
                'permission_id' => 112,
                'role_id' => 4,
            ),
            311 => 
            array (
                'permission_id' => 112,
                'role_id' => 6,
            ),
            312 => 
            array (
                'permission_id' => 112,
                'role_id' => 7,
            ),
            313 => 
            array (
                'permission_id' => 113,
                'role_id' => 1,
            ),
            314 => 
            array (
                'permission_id' => 113,
                'role_id' => 2,
            ),
            315 => 
            array (
                'permission_id' => 113,
                'role_id' => 4,
            ),
            316 => 
            array (
                'permission_id' => 113,
                'role_id' => 5,
            ),
            317 => 
            array (
                'permission_id' => 113,
                'role_id' => 6,
            ),
            318 => 
            array (
                'permission_id' => 113,
                'role_id' => 7,
            ),
            319 => 
            array (
                'permission_id' => 114,
                'role_id' => 1,
            ),
            320 => 
            array (
                'permission_id' => 114,
                'role_id' => 2,
            ),
            321 => 
            array (
                'permission_id' => 114,
                'role_id' => 4,
            ),
            322 => 
            array (
                'permission_id' => 114,
                'role_id' => 6,
            ),
            323 => 
            array (
                'permission_id' => 114,
                'role_id' => 7,
            ),
            324 => 
            array (
                'permission_id' => 115,
                'role_id' => 1,
            ),
            325 => 
            array (
                'permission_id' => 116,
                'role_id' => 1,
            ),
            326 => 
            array (
                'permission_id' => 116,
                'role_id' => 2,
            ),
            327 => 
            array (
                'permission_id' => 117,
                'role_id' => 1,
            ),
            328 => 
            array (
                'permission_id' => 118,
                'role_id' => 1,
            ),
            329 => 
            array (
                'permission_id' => 119,
                'role_id' => 1,
            ),
            330 => 
            array (
                'permission_id' => 120,
                'role_id' => 1,
            ),
            331 => 
            array (
                'permission_id' => 121,
                'role_id' => 1,
            ),
            332 => 
            array (
                'permission_id' => 122,
                'role_id' => 1,
            ),
            333 => 
            array (
                'permission_id' => 123,
                'role_id' => 1,
            ),
        ));
        
        
    }
}