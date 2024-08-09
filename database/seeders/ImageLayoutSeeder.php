<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ImageLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/footer_layout_default.jpeg', storage_path().'/app/public/footer_layout_default.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/footer_layout_only_info_nav.jpeg', storage_path().'/app/public/footer_layout_only_info_nav.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/footer_layout_only_form.jpeg', storage_path().'/app/public/footer_layout_only_form.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/footer_layout_only_info_contact.jpeg', storage_path().'/app/public/footer_layout_only_info_contact.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/footer_layout_only_info.jpeg', storage_path().'/app/public/footer_layout_only_info.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/footer_layout_only_nav_form.jpeg', storage_path().'/app/public/footer_layout_only_nav_form.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/footer_layout_only_nav.jpeg', storage_path().'/app/public/footer_layout_only_nav.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/header_layout_default.jpeg', storage_path().'/app/public/header_layout_default.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/header_layout_only_log.jpeg', storage_path().'/app/public/header_layout_only_log.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/header_layout_only_nav.jpeg', storage_path().'/app/public/header_layout_only_nav.jpeg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/logo-image-text-horizontal.jpg', storage_path().'/app/public/logo-image-text-horizontal.jpg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/logo-image-text-vertical.jpg', storage_path().'/app/public/logo-image-text-vertical.jpg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/logo-text-image-horizontal.jpg', storage_path().'/app/public/logo-text-image-horizontal.jpg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/logo-text-image-vertical.jpg', storage_path().'/app/public/logo-text-image-vertical.jpg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/imgCard.png', storage_path().'/app/public/imgCard.png');

        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_content_one.png', storage_path().'/app/public/layout_card_content_one.png');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_content_two.png', storage_path().'/app/public/layout_card_content_two.png');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_content_three.png', storage_path().'/app/public/layout_card_content_three.png');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_content_four.png', storage_path().'/app/public/layout_card_content_four.png');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_slide_one.jpg', storage_path().'/app/public/layout_card_slide_one.jpg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_slide_two.jpg', storage_path().'/app/public/layout_card_slide_two.jpg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_slide_three.jpg', storage_path().'/app/public/layout_card_slide_three.jpg');
        File::copy(base_path().'/packages/bcs/salt/resources/img/type_layout/layout_card_slide_four.jpg', storage_path().'/app/public/layout_card_slide_four.jpg');
    }
}
