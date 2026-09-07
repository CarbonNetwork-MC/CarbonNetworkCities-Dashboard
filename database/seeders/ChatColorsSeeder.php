<?php

namespace Database\Seeders;

use App\Models\ChatColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatColorsSeeder extends Seeder
{
    public const array COLORS = [
        [
            'name' => 'black',
            'open_tag' => '<black>',
            'close_tag' => '</black>',
            'is_bold' => false,
            'hex' => '#000000',
        ],
        [
            'name' => 'dark_blue',
            'open_tag' => '<dark_blue>',
            'close_tag' => '</dark_blue>',
            'is_bold' => false,
            'hex' => '#0000AA',
        ],
        [
            'name' => 'dark_green',
            'open_tag' => '<dark_green>',
            'close_tag' => '</dark_green>',
            'is_bold' => false,
            'hex' => '#00AA00',
        ],
        [
            'name' => 'dark_aqua',
            'open_tag' => '<dark_aqua>',
            'close_tag' => '</dark_aqua>',
            'is_bold' => false,
            'hex' => '#00AAAA',
        ],
        [
            'name' => 'dark_red',
            'open_tag' => '<dark_red>',
            'close_tag' => '</dark_red>',
            'is_bold' => false,
            'hex' => '#AA0000',
        ],
        [
            'name' => 'dark_purple',
            'open_tag' => '<dark_purple>',
            'close_tag' => '</dark_purple>',
            'is_bold' => false,
            'hex' => '#AA00AA',
        ],
        [
            'name' => 'gold',
            'open_tag' => '<gold>',
            'close_tag' => '</gold>',
            'is_bold' => false,
            'hex' => '#FFAA00',
        ],
        [
            'name' => 'gray',
            'open_tag' => '<gray>',
            'close_tag' => '</gray>',
            'is_bold' => false,
            'hex' => '#AAAAAA',
        ],
        [
            'name' => 'dark_gray',
            'open_tag' => '<dark_gray>',
            'close_tag' => '</dark_gray>',
            'is_bold' => false,
            'hex' => '#555555',
        ],
        [
            'name' => 'blue',
            'open_tag' => '<blue>',
            'close_tag' => '</blue>',
            'is_bold' => false,
            'hex' => '#5555FF',
        ],
        [
            'name' => 'green',
            'open_tag' => '<green>',
            'close_tag' => '</green>',
            'is_bold' => false,
            'hex' => '#55FF55',
        ],
        [
            'name' => 'aqua',
            'open_tag' => '<aqua>',
            'close_tag' => '</aqua>',
            'is_bold' => false,
            'hex' => '#55FFFF',
        ],
        [
            'name' => 'red',
            'open_tag' => '<red>',
            'close_tag' => '</red>',
            'is_bold' => false,
            'hex' => '#FF5555',
        ],
        [
            'name' => 'light_purple',
            'open_tag' => '<light_purple>',
            'close_tag' => '</light_purple>',
            'is_bold' => false,
            'hex' => '#FF55FF',
        ],
        [
            'name' => 'yellow',
            'open_tag' => '<yellow>',
            'close_tag' => '</yellow>',
            'is_bold' => false,
            'hex' => '#FFFF55',
        ],
        [
            'name' => 'white',
            'open_tag' => '<white>',
            'close_tag' => '</white>',
            'is_bold' => false,
            'hex' => '#FFFFFF',
        ],

        [
            'name' => 'bold_black',
            'open_tag' => '<black><bold>',
            'close_tag' => '</bold></black>',
            'is_bold' => true,
            'hex' => '#000000',
        ],
        [
            'name' => 'bold_dark_blue',
            'open_tag' => '<dark_blue><bold>',
            'close_tag' => '</bold></dark_blue>',
            'is_bold' => true,
            'hex' => '#0000AA',
        ],
        [
            'name' => 'bold_dark_green',
            'open_tag' => '<dark_green><bold>',
            'close_tag' => '</bold></dark_green>',
            'is_bold' => true,
            'hex' => '#00AA00',
        ],
        [
            'name' => 'bold_dark_aqua',
            'open_tag' => '<dark_aqua><bold>',
            'close_tag' => '</bold></dark_aqua>',
            'is_bold' => true,
            'hex' => '#00AAAA',
        ],
        [
            'name' => 'bold_dark_red',
            'open_tag' => '<dark_red><bold>',
            'close_tag' => '</bold></dark_red>',
            'is_bold' => true,
            'hex' => '#AA0000',
        ],
        [
            'name' => 'bold_dark_purple',
            'open_tag' => '<dark_purple><bold>',
            'close_tag' => '</bold></dark_purple>',
            'is_bold' => true,
            'hex' => '#AA00AA',
        ],
        [
            'name' => 'bold_gold',
            'open_tag' => '<gold><bold>',
            'close_tag' => '</bold></gold>',
            'is_bold' => true,
            'hex' => '#FFAA00',
        ],
        [
            'name' => 'bold_gray',
            'open_tag' => '<gray><bold>',
            'close_tag' => '</bold></gray>',
            'is_bold' => true,
            'hex' => '#AAAAAA',
        ],
        [
            'name' => 'bold_dark_gray',
            'open_tag' => '<dark_gray><bold>',
            'close_tag' => '</bold></dark_gray>',
            'is_bold' => true,
            'hex' => '#555555',
        ],
        [
            'name' => 'bold_blue',
            'open_tag' => '<blue><bold>',
            'close_tag' => '</bold></blue>',
            'is_bold' => true,
            'hex' => '#5555FF',
        ],
        [
            'name' => 'bold_green',
            'open_tag' => '<green><bold>',
            'close_tag' => '</bold></green>',
            'is_bold' => true,
            'hex' => '#55FF55',
        ],
        [
            'name' => 'bold_aqua',
            'open_tag' => '<aqua><bold>',
            'close_tag' => '</bold></aqua>',
            'is_bold' => true,
            'hex' => '#55FFFF',
        ],
        [
            'name' => 'bold_red',
            'open_tag' => '<red><bold>',
            'close_tag' => '</bold></red>',
            'is_bold' => true,
            'hex' => '#FF5555',
        ],
        [
            'name' => 'bold_light_purple',
            'open_tag' => '<light_purple><bold>',
            'close_tag' => '</bold></light_purple>',
            'is_bold' => true,
            'hex' => '#FF55FF',
        ],
        [
            'name' => 'bold_yellow',
            'open_tag' => '<yellow><bold>',
            'close_tag' => '</bold></yellow>',
            'is_bold' => true,
            'hex' => '#FFFF55',
        ],
        [
            'name' => 'bold_white',
            'open_tag' => '<white><bold>',
            'close_tag' => '</bold></white>',
            'is_bold' => true,
            'hex' => '#FFFFFF',
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::COLORS as $color) {
            ChatColor::query()->updateOrCreate(
                [
                    'name' => $color['name']
                ],
                [
                    'open_tag' => $color['open_tag'],
                    'close_tag' => $color['close_tag'],
                    'is_bold' => $color['is_bold'],
                    'hex' => $color['hex']
                ]
            );
        }
    }
}
