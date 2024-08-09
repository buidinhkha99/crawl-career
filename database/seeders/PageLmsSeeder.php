<?php

namespace Database\Seeders;

use App\Models\PageStatic;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Outl1ne\NovaMediaHub\MediaHub;

class PageLmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $img_logo = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents(base_path().'/packages/bcs/salt/resources/img/logoVimico.png')), 'bcs_log.png', 'default', 'public', 'public');

        $header = [
            'name' => 'header',
            'layout' => 'header',
            'structure' => [
                'key' => '',
                'layout' => 'header',
                'attributes' => [
                    'key' => '',
                    'name' => 'header',
                    'logo_url' => null,
                    'position' => 'fixed',
                    'background' => '#ffffff',
                    'components' => [
                        [
                            'key' => '',
                            'layout' => 'navigation-group',
                            'attributes' => [
                                'url' => null,
                                'items' => [
                                ],
                                'title' => 'Phần mềm sát hạch an toàn - vệ sinh lao động',
                            ],
                        ],
                    ],
                    'logo_image' => $img_logo->id,
                    'logo_image_url' => $img_logo->getUrl(),
                    'logo_text_option' => false,
                    'background_option' => 'color',
                    'logo_image_option' => 'upload',
                ],
            ],
        ];

        $footer = [
            'name' => 'info footer',
            'layout' => 'info-footer',
            'structure' => [
                'key' => '',
                'layout' => 'info-footer',
                'attributes' => [
                    'key' => '',
                    'name' => 'footer',
                    'image' => $img_logo->id,
                    'phone' => ' 0214 383 8886',
                    'title' => 'Chi nhánh Luyện đồng Lào Cai - Vimico',
                    'address' => ' Tân Hồng, Bát Xát, Lào Cai ',
                    'website' => 'https://vimico.vn/',
                    'image_url' => $img_logo->getUrl(),
                    'background' => '#faeec7',
                    'image_option' => 'upload',
                    'background_option' => 'color',
                ],
            ],
        ];
        $pages = [
            [
                'data' => [
                    'title' => 'Login',
                    'path' => '/login',
                    'language' => 'vi',
                    'required_auth' => 0,
                    'seo_title' => 'Login',
                    'seo_description' => 'Login',
                    'seo_keywords' => '["login"]',
                    'seo_og_image' => $img_logo->id,
                    'type_graph_image' => 'image',
                    'order' => 1,
                    'enabled' => 1,
                ],
                'section' => [
                    'name' => 'login',
                    'layout' => 'login',
                    'structure' => [
                        'key' => '',
                        'layout' => 'login',
                        'attributes' => [
                            'key' => '',
                            'name' => 'login',
                            'background' => '#ffffff',
                            'background_option' => 'color',
                            'detail_button_icon' => `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
  <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
</svg>`,
                            'detail_button_link' => null,
                            'detail_button_text' => 'Submit',
                            'detail_button_type' => 'button',
                            'click_detail_option' => 'button',
                            'redirect_after_login' => '/',
                            'detail_button_icon_option' => true,
                        ],
                    ],
                ],
            ],
            [
                'data' => [
                    'title' => 'User Info',
                    'path' => '/',
                    'language' => 'vi',
                    'required_auth' => 1,
                    'seo_title' => 'home',
                    'seo_description' => 'home',
                    'seo_keywords' => '["home"]',
                    'seo_og_image' => $img_logo->id,
                    'type_graph_image' => 'image',
                    'order' => 2,
                    'enabled' => 1,
                ],
                'section' => [
                    'name' => 'user info',
                    'layout' => 'user_info',
                    'structure' => [
                        'key' => '',
                        'layout' => 'user_info',
                        'attributes' => [
                            'key' => '',
                            'name' => 'user info',
                            'agree' => 'Xác nhận đúng không tin cá nhân',
                            'next_link' => '/exams',
                            'prev_link' => '/logout',
                            'background' => '#c5cae9',
                            'logout_link' => '/logout',
                            'background_option' => 'color',
                        ],
                    ],
                ],
            ],
            [
                'data' => [
                    'title' => 'Policy',
                    'path' => '/policy/{id}',
                    'language' => 'vi',
                    'required_auth' => 1,
                    'seo_title' => 'home',
                    'seo_description' => 'home',
                    'seo_keywords' => '["home"]',
                    'seo_og_image' => $img_logo->id,
                    'type_graph_image' => 'image',
                    'order' => 3,
                    'enabled' => 1,
                ],
                'section' => [
                    'name' => 'exam rule',
                    'layout' => 'exam_rule',
                    'structure' => [
                        'key' => '',
                        'layout' => 'exam_rule',
                        'attributes' => [
                            'key' => '',
                            'name' => 'exam rule',
                            'agree' => 'Tôi đã đọc và hoàn toàn đồng ý với Quy định của bài thi',
                            'background' => '#ffffff',
                            'description' => '<p><strong>Quy định b&agrave;i thi Đợt 3 - 2023</strong></p>
<p>H1: Excepteur sint occaecat cupidatat 23% non dg &agrave; ưng dproident, sunt in culpa qui officia</p>
<p>&nbsp;</p>
<p>H3: Excepteur sint occaecat&nbsp;</p>
<p> <p>Blockquote: Lorem ipsuua.&rdquo;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>&nbsp;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus. Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>H3: Excepteur sint occaecat&nbsp;</p>
<p>Blockquote: Lorem ipsuua.&rdquo;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>H3: Excepteur sint occaecat&nbsp;</p>
<p>Blockquote: Lorem ipsuua.&rdquo;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>H3: Excepteur sint occaecat&nbsp;</p>
<p>Blockquote: Lorem ipsuua.&rdquo;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>H3: Excepteur sint occaecat&nbsp;</p>
<p>Blockquote: Lorem ipsuua.&rdquo;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>
<p>Morbi a nisi sem.&nbsp;<br>Morbi eleifend elit id dapibus egestas.&nbsp;<br>Sed sagittis turpis eget malesuada tempus.&nbsp;</p>',
                            'next_button' => '/exam',
                            'prev_button' => '/exams',
                            'background_option' => 'color',
                        ],
                    ],
                ],
            ],
            [
                'data' => [
                    'title' => 'Exam',
                    'path' => '/exam/{id}',
                    'language' => 'vi',
                    'required_auth' => 1,
                    'seo_title' => 'home',
                    'seo_description' => 'home',
                    'seo_keywords' => '["home"]',
                    'seo_og_image' => $img_logo->id,
                    'type_graph_image' => 'image',
                    'order' => 4,
                    'enabled' => 1,
                ],
                'section' => [
                    'name' => 'exam work',
                    'layout' => 'exam_work',
                    'structure' => [
                        'key' => '',
                        'layout' => 'exam_work',
                        'attributes' => [
                            'key' => '',
                            'name' => 'sad',
                            'background' => '#ffffff',
                            'result_link' => '/result',
                            'background_option' => 'color',
                        ],
                    ],
                ],
            ],
            [

                'data' => [
                    'title' => 'Result',
                    'path' => '/result',
                    'language' => 'vi',
                    'required_auth' => 1,
                    'seo_title' => 'home',
                    'seo_description' => 'home',
                    'seo_keywords' => '["home"]',
                    'seo_og_image' => $img_logo->id,
                    'type_graph_image' => 'image',
                    'order' => 5,
                    'enabled' => 1,
                ],
                'section' => [
                    'name' => 'result',
                    'layout' => 'exam_result',
                    'structure' => [
                        'key' => '',
                        'layout' => 'exam_result',
                        'attributes' => [
                            'key' => '',
                            'name' => 'asd',
                            'background' => '#ffffff',
                            'background_option' => 'color',
                        ],
                    ],
                ],
            ],
            [

                'data' => [
                    'title' => 'Exams',
                    'path' => '/exams',
                    'language' => 'vi',
                    'required_auth' => 1,
                    'seo_title' => 'home',
                    'seo_description' => 'home',
                    'seo_keywords' => '["home"]',
                    'seo_og_image' => $img_logo->id,
                    'type_graph_image' => 'image',
                    'order' => 6,
                    'enabled' => 1,
                ],
                'section' => [
                    'name' => 'exam list',
                    'layout' => 'exam_list',
                    'structure' => [
                        'key' => '',
                        'layout' => 'exam_list',
                        'attributes' => [
                            'key' => '',
                            'name' => 'exam list',
                            'background' => '#ffffff',
                            'next_button' => '/policy',
                            'prev_button' => '/',
                            'background_option' => 'color',
                        ],
                    ],
                ],
            ],
        ];

        $section_header = Section::create($header);
        $section_footer = Section::create($footer);

        collect($pages)->each(function ($page) use ($section_header, $section_footer) {
            $pa = PageStatic::create($page['data']);
            $section = Section::create($page['section']);
            $pa->sections()->attach($section_header->id, ['order' => 0]);
            $pa->sections()->attach($section->id, ['order' => 1]);
            $pa->sections()->attach($section_footer->id, ['order' => 2]);
        });

    }
}
