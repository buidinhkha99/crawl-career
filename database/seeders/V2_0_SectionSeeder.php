<?php

namespace Database\Seeders;

use App\Models\PageStatic;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Outl1ne\NovaMediaHub\MediaHub;

class V2_0_SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $img_logo = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents(base_path().'/packages/bcs/salt/resources/img/bcs_log.png')), 'bcs_log.png', 'default', 'public', 'public');
        $pages =  [
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

        $section_header = Section::where('layout', 'header')->first();
        $section_footer = Section::where('layout', 'info-footer')->first();
        collect($pages)->each(function ($page) use ($section_header, $section_footer) {
            $pa = PageStatic::create($page['data']);
            $section = Section::create($page['section']);
            $pa->sections()->attach($section_header->id, ['order' => 0]);
            $pa->sections()->attach($section->id, ['order' => 1]);
            $pa->sections()->attach($section_footer->id, ['order' => 2]);
        });

        // update page
        $policy_page = PageStatic::where('path', '/policy')->first();
        $policy_page->path = "/policy/{id}";
        $policy_page->save();

        $exam_page = PageStatic::where('path', '/exam')->first();
        $exam_page->path = "/exam/{id}";
        $exam_page->save();


        // update section
        $user_info = Section::where('layout', 'user_info')->first();
        $structure = $user_info->structure;
        $structure['attributes']['next_link'] = '/exams';
        $user_info->structure = $structure;
        $user_info->save();

        $exam_rule = Section::where('layout', 'exam_rule')->first();
        $structure = $exam_rule->structure;
        $structure['attributes']['prev_button'] = '/exams';
        $exam_rule->structure = $structure;
        $exam_rule->save();

    }
}
