<?php

namespace Database\Seeders;

use App\Models\PageStatic;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Outl1ne\NovaMediaHub\MediaHub;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $img_card = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents(base_path().'/packages/bcs/salt/resources/img/imgCard.png')), 'imgCard.png', 'default', 'public', 'public');
        $img_logo = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents(base_path().'/packages/bcs/salt/resources/img/bcs_log.png')), 'bcs_log.png', 'default', 'public', 'public');

        $data_section = collect([
            [
                'layout' => 'header',
                'name' => 'header',
                'structure' => [
                    'key' => 'cgNMXDBsTf6BClEZ',
                    'layout' => 'header',
                    'attributes' => [
                        'key' => 'header',
                        'name' => 'header',
                        'logo_url' => '/',
                        'position' => 'fixed',
                        'logo_text' => 'BC Solution',
                        'background' => '#000000',
                        'components' => [
                            [
                                'key' => 'cT8CcOOACsMouyAx',
                                'layout' => 'navigation-group',
                                'attributes' => [
                                    'url' => '/',
                                    'items' => [
                                        [
                                            'key' => 'cEtBC2RRGkW8jmde',
                                            'layout' => 'navigations',
                                            'attributes' => [
                                                'url' => '/',
                                                'title' => 'aa',
                                            ],
                                        ],
                                    ],
                                    'title' => 'Home',
                                ],
                            ],
                            [
                                'key' => 'clcmUxrIaAnQQNCi',
                                'layout' => 'navigation-group',
                                'attributes' => [
                                    'url' => '/',
                                    'items' => [
                                        [
                                            'key' => 'cYBt0t6GhXutCdTM',
                                            'layout' => 'navigations',
                                            'attributes' => [
                                                'url' => '/',
                                                'title' => 'aaaa',
                                            ],
                                        ],
                                    ],
                                    'title' => 'About us',
                                ],
                            ],
                        ],
                        'logo_image' => (string) $img_logo->id,
                        'logo_text_font' => 'Poppins',
                        'logo_text_color' => '#ffffff',
                        'logo_text_option' => true,
                        'background_option' => 'color',
                        'logo_image_option' => 'upload',
                    ],
                ],
            ],
            [
                'layout' => 'card',
                'name' => 'card1',
                'structure' => [
                    'key' => 'c4o8QpgYiCo99giP',
                    'layout' => 'card',
                    'attributes' => [
                        'key' => 'card1',
                        'name' => 'card1',
                        'type' => 'banner',
                        'title' => 'Card 1',
                        'layout' => 'card-one',
                        'background' => '#191919',
                        'components' => [
                            [
                                'key' => 'cTKbr0l1AgyDieRm',
                                'layout' => 'card-layout',
                                'attributes' => [
                                    'image' => '1',
                                    'title' => 'Vestibulum ante ipsum',
                                    'layout' => 'image-info',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolor malesuada et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras veer inceptos himenaeos. Cras vehicula tellus nunc, ut pellentesque augue sagittis id. Proin gravida metus ut libero ornare volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                                    'image_option' => 'upload',
                                    'detail_button_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#ffffff"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/> </svg>',
                                    'detail_button_link' => '#',
                                    'detail_button_text' => 'Detail',
                                    'detail_button_type' => 'button',
                                    'click_detail_option' => 'button',
                                    'detail_button_color_text' => '#ffffff',
                                    'detail_button_icon_option' => true,
                                    'detail_button_color_background' => '#40a0a0',
                                ],
                            ],
                        ],
                        'background_option' => 'color',
                    ],
                ],
            ],
            [
                'layout' => 'card',
                'name' => 'card2',
                'structure' => [
                    'key' => 'ceShXiOzvBt1uXiZ',
                    'layout' => 'card',
                    'attributes' => [
                        'key' => 'card2',
                        'name' => 'card2',
                        'type' => 'banner',
                        'title' => 'Nam ac orci',
                        'layout' => 'card-two',
                        'background' => '#333333',
                        'components' => [
                            [
                                'key' => 'cbEBQQO7SdRKufT7',
                                'layout' => 'card-layout',
                                'attributes' => [
                                    'image' => '1',
                                    'title' => 'Vestibulum ante ipsum',
                                    'layout' => 'image-info',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolor malesuada et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras veer inceptos himenaeos. Cras vehicula tellus nunc, ut pellentesque augue sagittis id. Proin gravida metus ut libero ornare volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                                    'image_option' => 'upload',
                                    'detail_button_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#ffffff"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/> </svg>',
                                    'detail_button_link' => '#',
                                    'detail_button_text' => 'Detail',
                                    'detail_button_type' => 'button',
                                    'click_detail_option' => 'button',
                                    'detail_button_color_text' => '#ffffff',
                                    'detail_button_icon_option' => true,
                                    'detail_button_color_background' => '#40a0a0',
                                ],
                            ],
                            [
                                'key' => 'cOfs3YcMiZ6iR41i',
                                'layout' => 'card-layout',
                                'attributes' => [
                                    'image' => '1',
                                    'title' => 'Vestibulum ante ipsum',
                                    'layout' => 'image-info',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolor malesuada et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras veer inceptos himenaeos. Cras vehicula tellus nunc, ut pellentesque augue sagittis id. Proin gravida metus ut libero ornare volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                                    'image_option' => 'upload',
                                    'detail_button_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#ffffff"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/> </svg>',
                                    'detail_button_link' => '#',
                                    'detail_button_text' => 'Detail',
                                    'detail_button_type' => 'button',
                                    'click_detail_option' => 'button',
                                    'detail_button_color_text' => '#ffffff',
                                    'detail_button_icon_option' => true,
                                    'detail_button_color_background' => '#40a0a0',
                                ],
                            ],
                        ],
                        'background_option' => 'color',
                    ],
                ],
            ],
            [
                'layout' => 'card',
                'name' => 'card3',
                'structure' => [
                    'key' => 'cQTGq0rYyVan1ThS',
                    'layout' => 'card',
                    'attributes' => [
                        'key' => 'card3',
                        'name' => 'card3',
                        'type' => 'slider',
                        'title' => 'Morbi eget nisi vel lorem tincidunt',
                        'layout' => 'card-three',
                        'background' => '#191919',
                        'components' => [
                            [
                                'key' => 'c4ExN8BXFUzjOEq4',
                                'layout' => 'card-layout',
                                'attributes' => [
                                    'image' => '1',
                                    'title' => 'Vestibulum ante ipsum',
                                    'layout' => 'image-info',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolor malesuada et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras veer inceptos himenaeos. Cras vehicula tellus nunc, ut pellentesque augue sagittis id. Proin gravida metus ut libero ornare volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                                    'image_option' => 'upload',
                                    'detail_button_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#ffffff"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/> </svg>',
                                    'detail_button_link' => '#',
                                    'detail_button_text' => 'Detail',
                                    'detail_button_type' => 'button',
                                    'click_detail_option' => 'button',
                                    'detail_button_color_text' => '#ffffff',
                                    'detail_button_icon_option' => true,
                                    'detail_button_color_background' => '#40a0a0',
                                ],
                            ],
                            [
                                'key' => 'creeGXS8w8AeFiEb',
                                'layout' => 'card-layout',
                                'attributes' => [
                                    'image' => '1',
                                    'title' => 'Vestibulum ante ipsum',
                                    'layout' => 'image-info',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolor malesuada et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras veer inceptos himenaeos. Cras vehicula tellus nunc, ut pellentesque augue sagittis id. Proin gravida metus ut libero ornare volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                                    'image_option' => 'upload',
                                    'detail_button_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#ffffff"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/> </svg>',
                                    'detail_button_link' => '#',
                                    'detail_button_text' => 'Detail',
                                    'detail_button_type' => 'button',
                                    'click_detail_option' => 'button',
                                    'detail_button_color_text' => '#ffffff',
                                    'detail_button_icon_option' => true,
                                    'detail_button_color_background' => '#40a0a0',
                                ],
                            ],
                            [
                                'key' => 'cJCk5LdYIXTPGI7m',
                                'layout' => 'card-layout',
                                'attributes' => [
                                    'image' => '1',
                                    'title' => 'Vestibulum ante ipsum',
                                    'layout' => 'image-info',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolor malesuada et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras veer inceptos himenaeos. Cras vehicula tellus nunc, ut pellentesque augue sagittis id. Proin gravida metus ut libero ornare volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                                    'image_option' => 'upload',
                                    'detail_button_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#ffffff"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/> </svg>',
                                    'detail_button_link' => '#',
                                    'detail_button_text' => 'Detail',
                                    'detail_button_type' => 'button',
                                    'click_detail_option' => 'button',
                                    'detail_button_color_text' => '#ffffff',
                                    'detail_button_icon_option' => true,
                                    'detail_button_color_background' => '#40a0a0',
                                ],
                            ],
                        ],
                        'background_option' => 'color',
                    ],
                ],
            ],
            [
                'layout' => 'faq',
                'name' => 'faq',
                'structure' => [
                    'key' => 'cYj6URveSPpCulKL',
                    'layout' => 'faq',
                    'attributes' => [
                        'key' => 'faq',
                        'name' => 'faq',
                        'title' => 'faq',
                        'background' => '#000000',
                        'components' => [
                            [
                                'key' => 'c5JyfbLzXavbeo0l',
                                'layout' => 'faq',
                                'attributes' => [
                                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.
Morbi a nisi sem.
Morbi eleifend elit id dapibus egestas.
Sed sagittis turpis eget malesuada tempus.
Duis molestie tempor pulvinar.
Aenean tristique elementum dui et fringilla.
Aliquam vel pellentesque eros.
Vivamus et suscipit lectus.',
                                    'question' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. ',
                                ],
                            ],
                            [
                                'key' => 'cyWPGlGXKdZursBm',
                                'layout' => 'faq',
                                'attributes' => [
                                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.
Morbi a nisi sem.
Morbi eleifend elit id dapibus egestas.
Sed sagittis turpis eget malesuada tempus.
Duis molestie tempor pulvinar.
Aenean tristique elementum dui et fringilla.
Aliquam vel pellentesque eros.
Vivamus et suscipit lectus.',
                                    'question' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. ',
                                ],
                            ],
                            [
                                'key' => 'cPx4hb9TGu9iJ7NR',
                                'layout' => 'faq',
                                'attributes' => [
                                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.
Morbi a nisi sem.
Morbi eleifend elit id dapibus egestas.
Sed sagittis turpis eget malesuada tempus.
Duis molestie tempor pulvinar.
Aenean tristique elementum dui et fringilla.
Aliquam vel pellentesque eros.
Vivamus et suscipit lectus.',
                                    'question' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. ',
                                ],
                            ],
                        ],
                        'background_option' => 'color',
                    ],
                ],
            ],
            [
                'layout' => 'subscription',
                'name' => 'subscription',
                'structure' => [
                    'key' => 'cD63MODWwxwc4Okg',
                    'layout' => 'subscription',
                    'attributes' => [
                        'key' => 'subscription',
                        'name' => 'subscription',
                        'title' => 'Contact us',
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. ',
                        'background' => '#191919',
                        'background_option' => 'color',
                    ],
                ],
            ],
            [
                'layout' => 'footer',
                'name' => 'footer',
                'structure' => [
                    'key' => 'cDxiSLk2PCGbBtJJ',
                    'layout' => 'footer',
                    'attributes' => [
                        'key' => 'footer',
                        'name' => 'footer',
                        'layout' => 'footer-01',
                        'background' => '#1a237e',
                        'components' => [
                            [
                                'key' => 'cPcCjyOadilbZxWZ',
                                'layout' => 'information-footer',
                                'attributes' => [
                                    'logo_url' => '/',
                                    'logo_text' => 'BC Solution',
                                    'logo_image' => '2',
                                    'logo_text_font' => 'Poppins',
                                    'logo_text_color' => '#c5cae9',
                                    'logo_text_option' => true,
                                    'logo_image_option' => 'upload',
                                    'footer_information_icon' => [
                                        [
                                            'key' => 'c21f0Qmyo2x7TksE',
                                            'layout' => 'footer_information_icon_layout',
                                            'attributes' => [
                                                'button_icon' => '<svg fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><path d=\"M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z\"/></svg>',
                                                'button_link' => null,
                                                'button_type' => null,
                                            ],
                                        ],
                                        [
                                            'key' => 'cAYskE8ZHjzFbyru',
                                            'layout' => 'footer_information_icon_layout',
                                            'attributes' => [
                                                'button_icon' => '<svg fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\"><path d=\"M186.8 202.1l95.2 54.1-95.2 54.1V202.1zM448 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-42 176.3s0-59.6-7.6-88.2c-4.2-15.8-16.5-28.2-32.2-32.4C337.9 128 224 128 224 128s-113.9 0-142.2 7.7c-15.7 4.2-28 16.6-32.2 32.4-7.6 28.5-7.6 88.2-7.6 88.2s0 59.6 7.6 88.2c4.2 15.8 16.5 27.7 32.2 31.9C110.1 384 224 384 224 384s113.9 0 142.2-7.7c15.7-4.2 28-16.1 32.2-31.9 7.6-28.5 7.6-88.1 7.6-88.1z\"/></svg>',
                                                'button_link' => null,
                                                'button_type' => null,
                                            ],
                                        ],
                                        [
                                            'key' => 'cBFrGG3dADSmAYWO',
                                            'layout' => 'footer_information_icon_layout',
                                            'attributes' => [
                                                'button_icon' => '<svg fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 384 512\"><path d=\"M14 95.7924C14 42.8877 56.8878 0 109.793 0H274.161C327.066 0 369.954 42.8877 369.954 95.7924C369.954 129.292 352.758 158.776 326.711 175.897C352.758 193.019 369.954 222.502 369.954 256.002C369.954 308.907 327.066 351.795 274.161 351.795H272.081C247.279 351.795 224.678 342.369 207.666 326.904V415.167C207.666 468.777 163.657 512 110.309 512C57.5361 512 14 469.243 14 416.207C14 382.709 31.1945 353.227 57.2392 336.105C31.1945 318.983 14 289.5 14 256.002C14 222.502 31.196 193.019 57.2425 175.897C31.196 158.776 14 129.292 14 95.7924ZM176.288 191.587H109.793C74.2172 191.587 45.3778 220.427 45.3778 256.002C45.3778 291.44 73.9948 320.194 109.381 320.416C109.518 320.415 109.655 320.415 109.793 320.415H176.288V191.587ZM207.666 256.002C207.666 291.577 236.505 320.417 272.081 320.417H274.161C309.737 320.417 338.576 291.577 338.576 256.002C338.576 220.427 309.737 191.587 274.161 191.587H272.081C236.505 191.587 207.666 220.427 207.666 256.002ZM109.793 351.795C109.655 351.795 109.518 351.794 109.381 351.794C73.9948 352.015 45.3778 380.769 45.3778 416.207C45.3778 451.652 74.6025 480.622 110.309 480.622C146.591 480.622 176.288 451.186 176.288 415.167V351.795H109.793ZM109.793 31.3778C74.2172 31.3778 45.3778 60.2173 45.3778 95.7924C45.3778 131.368 74.2172 160.207 109.793 160.207H176.288V31.3778H109.793ZM207.666 160.207H274.161C309.737 160.207 338.576 131.368 338.576 95.7924C338.576 60.2173 309.737 31.3778 274.161 31.3778H207.666V160.207Z\"/></svg>',
                                                'button_link' => null,
                                                'button_type' => null,
                                            ],
                                        ],
                                        [
                                            'key' => 'cTzzFPWVbjnmvNhV',
                                            'layout' => 'footer_information_icon_layout',
                                            'attributes' => [
                                                'button_icon' => '<svg fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><path d=\"M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z\"/></svg>',
                                                'button_link' => null,
                                                'button_type' => null,
                                            ],
                                        ],
                                    ],
                                    'footer_information_introduction' => '2022 © Copyright BC Solution. All rights reserved. Hi there!',
                                ],
                            ],
                            [
                                'key' => 'cFRWgN1at4SUd4cW',
                                'layout' => 'form-footer',
                                'attributes' => [
                                    'title' => 'Contact',
                                ],
                            ],
                        ],
                        'background_option' => 'color',
                    ],
                ],
            ],
        ]);

        $page = PageStatic::create([
            'title' => 'Home',
            'path' => '/home',
            'language' => 'vi',
            'required_auth' => 0,
            'seo_title' => 'home',
            'seo_description' => 'home',
            'seo_keywords' => '["home"]',
            'seo_og_image' => $img_logo->id,
            'type_graph_image' => 'image',
        ]);
        $data_section->each(function ($data, $index) use ($page) {
            $section = Section::create([
                'layout' => $data['layout'],
                'name' => $data['name'],
                'structure' => $data['structure'],
            ]);
            $page->sections()->attach($section->id, ['order' => $index]);
        });
    }
}
