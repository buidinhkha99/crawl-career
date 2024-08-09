<?php

namespace App\Http\Controllers;

use App\Enums\ExaminationStatus;
use App\Models\ExaminationMockQuiz;
use App\Models\Lesson;
use App\Models\MockQuiz;
use App\Models\Question;
use App\Models\Subscription;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Outl1ne\NovaMediaHub\Models\Media;

class HomeController extends Controller
{
    public function index(): Response
    {
        SEOMeta::setTitle('BCS');
        SEOMeta::setDescription('BC Solution. All rights reserved');
        SEOMeta::addKeyword('acbxyz123');

        OpenGraph::setTitle('BCS');
        OpenGraph::setDescription('BC Solution. All rights reserved');
        OpenGraph::addImage('https://i.natgeofe.com/n/4f5aaece-3300-41a4-b2a8-ed2708a0a27c/domestic-dog_thumb_4x3.jpg');

        JsonLd::setTitle('BCS');
        JsonLd::setDescription('BC Solution. All rights reserved');
        JsonLd::addImage('https://i.natgeofe.com/n/4f5aaece-3300-41a4-b2a8-ed2708a0a27c/domestic-dog_thumb_4x3.jpg');

        return Inertia::render('Page', [
            'header' => [
                'type' => 'always_show', //fixed_position, always_show
                'background' => [
                    'type' => 'color',  // color, url
                    'data' => 'data:image/jpeg;base64,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',
                ],
                'logo' => [
                    'img' => 'data:image/jpeg;base64,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',
                    'text' => 'BC Solution',
                    'color' => '#FFFFFF',
                    'font' => 'Poppins',
                    'layout' => 'text-image-horizontal',
                    'url' => '/',
                ],
                'nav' => [
                    [
                        'title' => 'Home',
                        'url' => '/',
                    ],
                    [
                        'title' => 'Contact',
                        'nav_dropdown' => [
                            [
                                'title' => 'XXX',
                                'url' => '/',
                                'nav_dropdown' => [
                                    [
                                        'title' => 'About',
                                        'url' => 'https://reactjs.org',
                                    ],
                                    [
                                        'title' => 'About us',
                                        'url' => 'https://reactjs.org',
                                    ],
                                ],
                            ],
                            [
                                'title' => 'About us',
                                'url' => '#footer',
                            ],
                            [
                                'title' => 'us',
                                'url' => '/home',
                            ],
                        ],
                    ],
                    [
                        'title' => 'About us',
                        'url' => '/about',
                    ],
                    [
                        'title' => 'Project',
                        'url' => '1',
                    ],
                ],
                'language_list' => [
                    'VI',
                    'EN',
                ],
                'language_default' => 'VI',
                'search' => [],
                'account' => [],

            ],
            'components' => [
                //                 // CardTextOnly
                //                 [
                //                     'id' => 'CardTextOnly',
                //                     'type' => 'CardTextOnly',
                //                     'title' => 'Vestibulum ante ipsum',
                //                     'background' => [
                //                         'type' => 'color',
                //                         'data' => '#191919',
                //                     ],
                //                     'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolor malesuada et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras veer inceptos himenaeos. Cras vehicula tellus nunc, ut pellentesque augue sagittis id. Proin gravida metus ut libero ornare volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut. Ut gravida pretium tortor et mattis. Vivamus vulputate commodo elit, et fringilla eros consequat sed. Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                     'config_button' => [
                //                         'icon' => [
                //                             'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                //                                 <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                //                                 </svg>',
                //                             'url' => '/',
                //                         ],
                //                         'text' => 'Detail',
                //                         'button_type' => 'submit',
                //                         'url' => 'https://reactjs.org',
                //                         'size' => 'large',
                //                         'color_background' => '#40A0A0',
                //                         'color_text' => '#ffffff',
                //                     ],
                //                 ],
                //                 // Banner
                //                 [
                //                     'id' => 'Banner',
                //                     'type' => 'Banner',
                //                     'background' => [
                //                         'type' => 'color',  // color, url
                //                         'data' => '#333333  ',
                //                     ],
                //                     'img' => 'https://thumbs.dreamstime.com/b/aerial-view-lago-antorno-dolomites-lake-mountain-landscape-alps-peak-misurina-cortina-di-ampezzo-italy-reflected-103752677.jpg',
                //                     'url' => 'https://facebook.com',
                //                 ],
                // CardContentOne
                [
                    'id' => 'CardContentOne',
                    'type' => 'CardContentOne',
                    'background' => [
                        'type' => 'color',  // color, url
                        'data' => 'https://img.vn/uploads/thuvien/singa-png-20220719150401Tdj1WAJFQr.png',
                    ],
                    'title' => 'Nam ac orci',
                    'components' => [
                        [
                            'type' => 'cardSlide',
                            'layout' => 'image-info',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                                        </svg>',
                                    'url' => '#footer',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#40A0A0',
                                'color_text' => '#ffffff',
                            ],
                        ],
                    ],
                ],
                // [
                //     'id' => 'CardContentOne1',
                //     'type' => 'CardContentOne',
                //     'background' => [
                //         'type' => 'color',  // color, url
                //         'data' => 'black',
                //     ],
                //     'title' => 'Nam ac orci',
                //     'components' => [
                //         [
                //             'type' => 'cardSlide',
                //             'layout' => 'image-info',
                //             'img' => 'storage/imgCard.png',
                //             'title' => 'Vestibul',
                //             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //             'config_button' => [
                //                 'icon' => [
                //                     'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                //                         <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                //                         </svg>',
                //                     'url' => '/',
                //                 ],
                //                 'text' => 'Detail',
                //                 'button_type' => 'submit',
                //                 'url' => 'https://reactjs.org',
                //                 'size' => 'large',
                //                 'color_background' => '#40A0A0',
                //                 'color_text' => '#ffffff',
                //             ],
                //         ]
                //     ],
                // ],
                // CardContentTwo
                [
                    'id' => 'CardContentTwo',
                    'type' => 'CardContentTwo',
                    'background' => [
                        'type' => 'color',  // color, url
                        'data' => '#080808',
                    ],
                    'title' => 'Nam ac orci',
                    'components' => [
                        [
                            'type' => 'card',
                            'layout' => 'image-info',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                                        </svg>',
                                    'url' => '/',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#40A0A0',
                                'color_text' => '#ffffff',
                            ],
                        ],
                        [
                            'type' => 'card',
                            'layout' => 'image-info',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                                        </svg>',
                                    'url' => '/',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#40A0A0',
                                'color_text' => '#ffffff',
                            ],
                        ],
                        [
                            'type' => 'card',
                            'layout' => 'info-image',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                                        </svg>',
                                    'url' => '/',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#40A0A0',
                                'color_text' => '#ffffff',
                            ],
                        ],

                    ],
                ],
                //                 // CardContentThree
                //                 [
                //                     'id' => 'CardContentThree',
                //                     'type' => 'CardContentThree',
                //                     'background' => [
                //                         'type' => 'color',  // color, url
                //                         'data' => '#191919',
                //                     ],
                //                     'title' => 'Nam ac orci',
                //                     'components' => [
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                     ],
                //                 ],
                //                 // CardContentFour
                //                 [
                //                     'id' => 'CardContentFour',
                //                     'type' => 'CardContentFour',
                //                     'background' => [
                //                         'type' => 'color',  // color, url
                //                         'data' => '#080808',
                //                     ],
                //                     'title' => 'Nam ac orci',
                //                     'components' => [
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                     ],
                //                 ],
                // CardSlideOne
                // [
                //     'id' => 'CardSlideOne',
                //     'type' => 'CardSlideOne',
                //     'background' => [
                //         'type' => 'color',  // color, url
                //         'data' => '#191919',
                //     ],
                //     'title' => 'Nam ac orci',
                //     'components' => [
                //         [
                //             'type' => 'cardSlide',
                //             'layout' => 'image-info',
                //             'img' => 'storage/imgCard.png',
                //             'title' => 'Vestibulum ante ipsum',
                //             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //             'config_button' => [
                //                 'icon' => [
                //                     'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                //                         <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                //                         </svg>',
                //                     'url' => '/',
                //                 ],
                //                 'text' => 'Detail',
                //                 'button_type' => 'submit',
                //                 'url' => 'https://reactjs.org',
                //                 'size' => 'large',
                //                 'color_background' => '#40A0A0',
                //                 'color_text' => '#ffffff',
                //             ],
                //         ],
                //         [
                //             'type' => 'cardSlide',
                //             'layout' => 'image-info',
                //             'img' => 'storage/imgCard.png',
                //             'title' => 'Vestibulum ante ipsum',
                //             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //             'config_button' => [
                //                 'icon' => [
                //                     'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                //                         <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                //                         </svg>',
                //                     'url' => '/',
                //                 ],
                //                 'text' => 'Detail',
                //                 'button_type' => 'submit',
                //                 'url' => 'https://reactjs.org',
                //                 'size' => 'large',
                //                 'color_background' => '#40A0A0',
                //                 'color_text' => '#ffffff',
                //             ],
                //         ],
                //         [
                //             'type' => 'cardSlide',
                //             'layout' => 'image-info',
                //             'img' => 'https://i.pinimg.com/736x/ba/92/7f/ba927ff34cd961ce2c184d47e8ead9f6.jpg',
                //             'title' => 'Vestibulum ante ipsum',
                //             'description' => 'Sed quis leo ante',
                //             'config_button' => [
                //                 'icon' => [
                //                     'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                //                         <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                //                         </svg>',
                //                     'url' => '/',
                //                 ],
                //                 'text' => 'Detail',
                //                 'button_type' => 'submit',
                //                 'url' => 'https://reactjs.org',
                //                 'size' => 'large',
                //                 'color_background' => '#40A0A0',
                //                 'color_text' => '#ffffff',
                //             ],
                //         ],
                //         [
                //             'type' => 'card',
                //             'layout' => 'image-info',
                //             'img' => 'https://i.pinimg.com/736x/ba/92/7f/ba927ff34cd961ce2c184d47e8ead9f6.jpg',
                //             'title' => 'Vestibulum ante ipsum',
                //             'description' => 'Sed quis leo ante',
                //             'config_button' => [
                //                 'icon' => [
                //                     'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                //                         <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                //                         </svg>',
                //                     'url' => '/',
                //                 ],
                //                 'text' => 'Detail',
                //                 'button_type' => 'submit',
                //                 'url' => 'https://reactjs.org',
                //                 'size' => 'large',
                //                 'color_background' => '#40A0A0',
                //                 'color_text' => '#ffffff',
                //             ],
                //         ],
                //         [
                //             'type' => 'card',
                //             'layout' => 'image-info',
                //             'img' => 'https://i.pinimg.com/736x/ba/92/7f/ba927ff34cd961ce2c184d47e8ead9f6.jpg',
                //             'title' => 'Vestibulum ante ipsum',
                //             'description' => 'Sed quis leo ante',
                //             'config_button' => [
                //                 'icon' => [
                //                     'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                //                         <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                //                         </svg>',
                //                     'url' => '/',
                //                 ],
                //                 'text' => 'Detail',
                //                 'button_type' => 'submit',
                //                 'url' => 'https://reactjs.org',
                //                 'size' => 'large',
                //                 'color_background' => '#40A0A0',
                //                 'color_text' => '#ffffff',
                //             ],
                //         ],
                //     ],
                // ],
                // CardSlideTwo
                [
                    'id' => 'CardSlideTwo',
                    'type' => 'CardSlideTwo',
                    'background' => [
                        'type' => 'color',  // color, url
                        'data' => '#080808',
                    ],
                    'title' => 'Nam ac orci',
                    'components' => [
                        [
                            'type' => 'card',
                            'layout' => 'image-info',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                                        </svg>',
                                    'url' => '/',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#40A0A0',
                                'color_text' => '#ffffff',
                            ],
                        ],
                        [
                            'type' => 'card',
                            'layout' => 'image-info',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                    'url' => '/',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#fff',
                                'color_text' => '#227C9D',
                            ],
                        ],
                        [
                            'type' => 'card',
                            'layout' => 'image-info',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                    'url' => '/',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#fff',
                                'color_text' => '#227C9D',
                            ],
                        ],
                        [
                            'type' => 'card',
                            'layout' => 'image-info',
                            'img' => 'storage/imgCard.png',
                            'title' => 'Vestibulum ante ipsum',
                            'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                            'config_button' => [
                                'icon' => [
                                    'data' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.9043 6.52067 11 6.28333 11 6C11 5.71667 10.9043 5.479 10.713 5.287C10.521 5.09567 10.2833 5 10 5C9.71667 5 9.47933 5.09567 9.288 5.287C9.096 5.479 9 5.71667 9 6C9 6.28333 9.096 6.52067 9.288 6.712C9.47933 6.904 9.71667 7 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6873 3.825 17.975 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.262667 12.6833 0 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31267 4.88333 2.025 3.825 2.925 2.925C3.825 2.025 4.88333 1.31233 6.1 0.787C7.31667 0.262333 8.61667 0 10 0C11.3833 0 12.6833 0.262333 13.9 0.787C15.1167 1.31233 16.175 2.025 17.075 2.925C17.975 3.825 18.6873 4.88333 19.212 6.1C19.7373 7.31667 20 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6873 15.1167 17.975 16.175 17.075 17.075C16.175 17.975 15.1167 18.6873 13.9 19.212C12.6833 19.7373 11.3833 20 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="white"/>
                                        </svg>',
                                    'url' => '/',
                                ],
                                'text' => 'Detail',
                                'button_type' => 'submit',
                                'url' => 'https://reactjs.org',
                                'size' => 'large',
                                'color_background' => '#40A0A0',
                                'color_text' => '#ffffff',
                            ],
                        ],
                    ],
                ],
                //                 // CardSlideThree
                //                 [
                //                     'id' => 'CardSlideThree',
                //                     'type' => 'CardSlideThree',
                //                     'background' => [
                //                         'type' => 'color',  // color, url
                //                         'data' => '#191919',
                //                     ],
                //                     'title' => 'Nam ac orci',
                //                     'components' => [
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                     ],
                //                 ],
                //                 // CardSlideFour
                //                 [
                //                     'id' => 'CardSlideFour',
                //                     'type' => 'CardSlideFour',
                //                     'background' => [
                //                         'type' => 'color',  // color, url
                //                         'data' => '#080808',
                //                     ],
                //                     'title' => 'Nam ac orci',
                //                     'components' => [
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                         [
                //                             'type' => 'card',
                //                             'layout' => 'image-info',
                //                             'img' => 'storage/imgCard.png',
                //                             'title' => 'Vestibulum ante ipsum',
                //                             'description' => 'Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dolSed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol',
                //                             'config_button' => [
                //                                 'icon' => [
                //                                     'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                //                                     'url' => '/',
                //                                 ],
                //                                 'text' => 'Detail',
                //                                 'button_type' => 'submit',
                //                                 'url' => 'https://reactjs.org',
                //                                 'size' => 'large',
                //                                 'color_background' => '#fff',
                //                                 'color_text' => '#227C9D',
                //                             ],
                //                         ],
                //                     ],
                //                 ],
                //                 // Subscription
                //                 [
                //                     'id' => 'subscription',
                //                     'type' => 'Subscription',
                //                     'title' => 'Sign up for our New letter',
                //                     'background' => [
                //                         'type' => 'url',  // color, url
                //                         'data' => 'black',
                //                     ],
                //                     'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.',
                //                 ],
                //                 // FAQ
                //                 [
                //                     'id' => 'faq',
                //                     'type' => 'Faq',
                //                     'background' => [
                //                         'type' => 'color',  // color, url
                //                         'data' => '#333333',
                //                     ],
                //                     'title' => 'FAQ’s',
                //                     'components' => [
                //                         [
                //                             'question' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.',
                //                             'answer' => '
                // # heading
                // * list
                // * items
                // | S/N | Pet | Image |
                // --|--|--|
                // 1 | Cat |![A cat looking at you](https://i.guim.co.uk/img/media/26392d05302e02f7bf4eb143bb84c8097d09144b/446_167_3683_2210/master/3683.jpg?width=465&quality=45&auto=format&fit=max&dpr=2&s=68615bab04be2077a471009ffc236509) |
                // | 2 | Dog |![A dog looking at you](https://ichef.bbci.co.uk/news/976/cpsprodpb/17638/production/_124800859_gettyimages-817514614.jpg)|
                // ',
                //                         ],
                //                         [
                //                             'question' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.',
                //                             'answer' => '
                //                     # heading
                //                     * list
                //                     * items
                //                     ',
                //                         ],
                //                         [
                //                             'question' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales velit finibus ut.',
                //                             'answer' => '
                //                     # heading Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vulputate tempus urna. Nam lacinia nisl eros, vitae sodales vel
                //                     * list
                //                     * items
                //                     | S/N | Pet | Image |
                //                     |--|--|--|
                //                     | 1 | Cat |![A cat looking at you](https://i.guim.co.uk/img/media/26392d05302e02f7bf4eb143bb84c8097d09144b/446_167_3683_2210/master/3683.jpg?width=465&quality=45&auto=format&fit=max&dpr=2&s=68615bab04be2077a471009ffc236509) |
                //                     | 2 | Dog |![A dog looking at you](https://ichef.bbci.co.uk/news/976/cpsprodpb/17638/production/_124800859_gettyimages-817514614.jpg)|
                //                     ',
                //                         ],
                //                     ],

                //                 ],
                // Post
                // [
                //     'id' => 'post',
                //     'type' => 'Post',
                // ],
                // Post Detail
                // [
                //     'id' => 'postDetail',
                //     'type' => 'PostDetail'
                // ],
                //                 // CardContentExpand,
                //                 [
                //                     'id' => 'CardContentExpand',
                //                     'type' => 'CardContentExpand',
                //                     'background' => [
                //                         'type' => 'color',  // color, url
                //                         'data' => '#333333'
                //                     ],
                //                     'title' => "VESTIBULUM ANTE IPSUM",
                //                     'layout' => "image-info",
                //                     'img' => 'storage/imgCard.png',
                //                     'title' => "Vestibulum ante ipsum",
                //                     'description' =>
                //                     "Sed quis leo ante. Proin quis sollicitudin magna. Nullam congue condimentum arcu, et scelerisque dol",
                //                 ],
                //    Topic
                [
                    'id' => 'topic',
                    'type' => 'Topic',
                ],
                //    Popular
                [
                    'id' => 'popular',
                    'type' => 'Popular',
                ],
            ],
            'footer' => [
                'theme' => 'white',
                'id' => 'footer',
                'background' => 'green',
                'background' => [
                    'type' => 'color',  // color, url
                    'data' => 'black',
                ],
                'components' => [
                    [
                        'type' => 'Info',
                        'logo' => [
                            'img' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAoHCBISFRgSEhURGBESEREREhISEhEREhERGBQaGRgYGRgcIy4lHB4sHxgYJjomLC8xNTU1GiQ7QDszPy40NTEBDAwMEA8QHhISHjQrISsxNDQ0NDQ0NDQ2NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0PzQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NP/AABEIARMAtwMBIgACEQEDEQH/xAAbAAABBQEBAAAAAAAAAAAAAAADAAECBAUHBv/EAD0QAAICAQMBBgQCCAUDBQAAAAECABEDBBIhMQUTIkFRYQYycYGRsQcUI0JSocHwM2KC0eEVcvEkNEOSwv/EABgBAAMBAQAAAAAAAAAAAAAAAAABAgME/8QAJxEBAQACAQQBBAEFAAAAAAAAAAECEQMSITFBURMyYYEEFCJxwfD/2gAMAwEAAhEDEQA/ANBdLDJhqXGSpFUl6GwFxQoSWtgkdsfSWwQscJDhI+2Gi2rbJIJD7Y4SGhsApIlZZKxmSPQ2AstYxA7Y2LF+3B3NXcnw2dlhxzt9eesCXRIsYRxB1KTTSSxqkgIJSkTJCNUo0akCsMFkWWAVmWCdZbYQDLFTVMiRQuQRST2M6cyaYxCMJIDiGj2HtkSIQiJRGCRJIrJqI+2ABCxmhgsgyxADdJqYmSNUAfbcz0y/+vVNx2nRudl+Et3g5r1ofyk+2dSMWnyOSwIxsqlTTb28K0fLkjmctTVZd2/vRvAoP3i7x9DfEjLLpXjjcnaXEERKXwxmGTTY2DMzAEZGetxyXbnjjqb48iJplZcu5tFmroGpMCS2ySrGSO2SVZILJbYJQCSLLC1IESgrssE4ll1lTJA1bJFE8UhTQcRLDusFtjCJEQEkBHqATWOREgk9sCCCxMkIRIkQCtUiRLGyN3cRsD4y07ZNFmVFLMBjfaBZKrlRm4/7Q05WmlTcb6biPPqSaH5fhO7onr0o39Jw98fzEbfE1g89Dv4H+X38pjyzw24b2rqPwBgZNEoZSu7LmZQQQSm4hT9wLueiIkezCGwYmFUcGIiulFB0hyk1x8Rlld5ULbHCye2KpSTBY9SYEeoEGVg3MORAZBHCAcyrkMO5ld4jiu8Ud4ojbDiQIlplECwiWgqx9kII8aUFWEAjhZICPZUMrJLjk6k1WADCRFIYpHCwClqfAjt/Djc/gpM4eyEXwB8t0b6hx4PVT06+s7f234dNmbzGnzGvU7DOMZ1IZvCV28WSx7s7clofP2+8w5fTfh9uwfDdtpNPe2xgxqdvK2o2mvbiaO2UPhJD+qICmwocqbASdlZGoWeelTVZZrj9sZZfdQGWNthGEjKQYCIxbpFjAHMr5YbdK+QwCu8rNLLwDCBgMIo7CKIN1zIGRZolMWlpSQijgRpOJIRlWFCQKmAhFEYLJKIwntkak1iIkhk/E3/tM/XnEy+EFj4vDwByTz0nI9bpmDkMQTfh4cB0vILPnY9P8s638V3+quAAWZ8CAMCVJbOg5A5qci1OTGXehSFmAbaTsasnF0OLviuAamPK6OHw6v8ABW0aYorMwTM43MCCxZVcnnyO6x7ETccTB+DHcrlDqqtvR9q7aCtiVR8vB+TqKHsJ6Bppj4Y5/dQHEC0sNK7TRKJMYmIxoEi0E8KYMiAAKwTCWysA6wCo6xQrLFFpW15pPHIMJJIKF2yQESmItEkRYVRK4MKrR6AoWOBGUycREI8gTHLQDA+ONQuPSl237RlwkjGaeg4bg/aclz9oPkZ82xEU7bQYyUFgqbF/hXQzrnxY2PZiXIqsj6lVZGXcHvHkpa+pH06+U5bqezwMmRdmxUcg4ywWltqqga4q+D9JhyeXTw+Hvv0ddod93xojwaewSNoZd4O0DoK2+nNnznsW5nlPhPHjxZRjVERzokbImNSFLb73A0N3zmeqeaYeGXJ5DyQDQrQLTVkgYojFAjERbZKPUDBYQbrLDCCYQCqyxQrCKBj2JNSIPbEIlUW4hIgRzGSYhFg0MKBAk1MmGgxJARAiYrj1EFgHn/ibMFyaUGz+0ztVAg3hZPFZAA8fr5zwbjdlyMj7h3p2kbiW8V1xV2OOD7+c9v8AESh9Vgx82MGZumMqA7oLbfxR2162RVdZ5FsZ7xw3dtkGbJuHzq3gAUcnxDkDz6/ec/J5/bq4vH6ek7AzIufSoti9JlQjgi2RHom/m/ZnqBwPsPYvPDaADG+hcuXt0XvB3e23xlKaiWJ5Asn93ynvHWaYe2PJ6V2gmEsMsgVmrJXIjVCMsiRAjCNFFAHMG0nIOYGGyxo7GNADRtsa4rkxolcVyIkwspKSwimDAhUECTEmIyiTCxAwhFWILCKsQeO7Uz4zr2RrO3SLjNFapn30QeSePLpRM8rk7s5C6B3DZi3eMQ3dnvAoDcnf0rz8p6Tb3us1RXd4WxoTYCEoGFXdhgaN0QPS+Z5ztnHk/WS5UF1fGciqlpuGS9vBoUCDyOSek58/H7deH+mlqNUiabBkIO5MmmdiNgXwCt1A8ir4FEX0nR2nO9dpmOkYszPWNQu3YpKIfD7UPMcnwk9bv3XZWcZcGLIOj4kb7lRf85eF7/plyTt+x2EGVhiJEiasldlgXEtOIJllFoAiRhWEE0AYmDYxMYNmgDtFBF4ojGV5IGV1aGVoQ6sJJwSmTUwKpAQyCDBhFMCEWSBgwZIRAUQqmuT0HJglnjviz45x6R30oRzkCAOwUlUDpYrpZojzk5XSscbldRi4u2QgyMiu+TJqjnDKv7NVKKOTYs8EG+gP4ZXaTZsmU5FtkLI+Tw7QCUTcAKoABBQPnzMpO0dPs2btVXNBU2+RAHD9B/SRy9p42Lm9VeT5gCQB4rpfFx9py7t8uvU9PdaXW4mxDG5O9dMcYVB3Zcd1RO0kb3IP3qq4uej+CMwfSIvP7O8fPXiiP5Gcd/6jhsMf1rcqhFNK3Auibfrz19h6Tb+HPjldECgTM+Jm3MHRFIagC1hifKaY5asRljuXTsjSDRtPnGREyLe3IiOAQQaZQRY+8TGbxzIEwbGOxg3Mog3ME8I0C0YDcwDmFaAcxGg7RQTR5Oz0sAyStBAxb4SnVtHh0Moo8so0rZVaEkDAq0mGgkdTJhpW3QitALGOcx/SPoGTVnUKPDk02FL2F6yB3BO0daULxOmI05x8f9qOdUmHGEJGMBd+4rvJNjgjafc+ky5fDXi+55jRafK7qrJQosw7v5kAJJ3dAfbzv2lVf1hgSMbccsO6KhDYBAJHiFE8j0mkvbLY1zeDGTixGqD7QWdUVTbeLhyTVdJFtVq+NyaYFjVVlJVipNGmocDrdflOfW/To3r2oHFlOLeEfwvtLdyQzKVtbQi18Vi+a4PMinZ2TMxwspByABfAFCmweGA5vkV9JeXtDMpyY3TESMIyoU3gMFcAm2b+Euea6cwB12VQmQBNpN2qZFe7BBILeEURzz7RasN3cIFAUdFAUfQChBtBaDVDLjTJx40BNdNw4b+YMK07Jdxx2aobQbQrQLykhtAtDNAOYwA5gHMNkld5NMNjFINFEvQtxrgN8IrRQVYRodHlRGhlaUmratJ7pU3yXexlpbVoRTKaZJYxm4BaOQIpdvlVSx+gFzlnajHPtyDcXXNqnyFSECBfCoJPDAOHP1M918T6vusAUfPlyKijpxYJs+QvaL955DtrKVd0Szjwpi06P+5SrTkkGuWv+frMOWtuKdtsJMIy4xiQMz5tXg0+4EBmVSGKqTQ4LL4j6+gnQj2Hn4F6zw1X7Ts4X9aHPXzueW+GcKnVaPEnKb8mqc+jbGZfqB3a8/SdXbrFxTcPlysrlnxL2W+DU4MuQZSMy5MLHI+FutDgY/l+cm/aYmTSuyLkVMhONwSMlbVKv0CgUUpufX06mdC/SLiJ0yOtbsepQWegV1ZDf4ieSx7ciZEQuwKpn7sh3JOQdUpgRXg9vAR5ycp/d+lYXeO3rP0fdo97hbHu3bMjFGraXS6J22a5o/6xPUtOW/Cfa5x6jH3hsupxvwFKIngsjzHCNflVTqLzXiu5plyzV2ExgWMk5gGebMid5Wd5J2gWaIzOYFxCs0hEFdhGhykUWlbZ6NCK8qI0KHkyrq0HhVeU1eFRo9pWS0dTBAw2MRksYlmhgWVMAngO1fj7W6XUPg7rTlUchHIfxY91Bqv+yDHcpjO5Y43Lw9T2oceo1LY8uxtPpse/IjnwHZ4vF/r2mvPu543tUnKi41QDJkbfYpRuPVCAeD05Pt9qGX4iyKXKlS2TYMmNTkRWo3uJA5JJN3fUyuNY/eKUxpvBXJtG9bf5vFxz69Zy5ZWunGTF7j4H06nXOVHh0+mGMHjk0iD8n/GdAY8znnwZpNWFy5NPk0yeLHhZs2PJkZtq7hVOtcOPW7E3cY7UayNToqDFbGkyMD7j9pNePtiy5NW+V74s0+/RZ1P7uPvB9UIf/wDM59oNWiqmUKtENiOMorlQxLDbyDfSua4up63UJ2iUfvNXolxKrrkZ9G4QJt8RJOXgUZzQ9oPioY8mHmlJXceEJ2sPH05P4SeTe5VcetWNNMiF37tV3hjk3+BsiY0B7xFdTz4aXbXJa+J03sDtAajTpkDBiBsc+rLxf3FH7zimv7XyFw5GIs24nJ3W1r5BYU/WuLu6AHtLeD4s1WlxqNLlXabUq+nCttUEhjbN611JMWGWrtWWPVHa3lbIZhfBev1WfT95qmDZWewAqpsQopUUPPmzfrNjI86Jdzbms1dBu0CzR3MFcDiYkgIMGPcDSLRQZii2NMbdJKYANJgzLbSrCNDK0pq8KhMqUrF1DLKGUUuW8IMuM60dMs41r8WpOVwxR2GR0DullgjsLPP93Ot6/XDTYjkItqrGvmzkcD+v2nMNTpsjM7AH/EDAkNTg3v3ADrZ4A9r85jz5eI24cb3qkMefrtw+I0LxjqOvU+xjqNQDuAxAkhQdgvcK9/Yy8NM9tavtBsblYkqOWtR1NDw168wGfTZHZ2GMgMinGGRuGtbD0Pl69Ofm9pzdTosRx6jWKKR1UWFpfCN/Hlft19hHXLrBwMtC9tAit30v+fSRfSNZpDWxdto195xd1xs68Dn5vaMunfcPAfkYGkN95tNEc/4ft16fY6vyXT+EnfWUQctjlWB2MCxBHQ9eold8WpF26eEEGseHqbquPcdIZdO/h3IeMbh9qEEud1bCSNq8jg0fl9PCw058NoDStv2qVDAk8pZ8IF831r6APf5HTfhXfBqB1dfD18GLm/Tj8oF9Pn83HhPPhx8g+lCaWnViqoUFozOWC0u3w0u2yVsefqp6g8h/Vm48iH3HkUTwNgF+H/uHv1s2TI+mvZ/o0wsqZ2yOWc5Ma1Y2hQpogD6n8J655zr4W7V/VHpyO7c7XIIoKSNh68kbhz536zorm+R0PII8xOrhylmnLy42ZbV3EHUM0GTNWRopEtIM8W1aSZopWZ48nZ6ZCmTHM8Vo/jZhxlxBvItjNH/6n/eep7L7XwamxiYlgNzIVKso/KZStN7aCJLmJZXRZbxrNImrGJJfwYx1PQc/SVcAmV8VdqrjQ4txUshORlosuOuAAepPp6fWVcpjNomNyuo818d9tOXRgitiCk4gW8QW6LlfLd5H0r3nnMna7KBaIAB4mZ3AVvQUbscdPMe01cHZmXUDvyyqQjMWygbceJflG29xJPlXnMjs3TMzd86qcaqWXeFsv/Cb6BeTfsJy5fNdWHbtGpqsubaiYbx5W2OzggY1Yf8AxsCSQSCOBzwB06VdPosWMrvdj4nbGHcoo7wU68m6oHxH6wHaXaOTY2TGCyBhjObgLvY2oRTwW6+9c9DZsdj6bTDCMmZcmTVZ1JG9yyKCbx5ECEMzCirBjxZqvNYYWzv2PLOS/KH/AFPEgPd/uY0Hg2oSu8AAEAni+h8r85ZGQtZNC6rdmwghnHIIOQcdaPsekfF8NZ9T3jYdOUDtSl2cKiEo9ISdm0MH9Tz5zUwfo1fb4ji5okd7lXm/OgRNJwz4Z3mvyw83aOwr5hmbkZcZoJYvws3JFGvP1Mic+kyBlYpwm3cCENFiTzwx6WQB5ia+X9HufGVfGMbEXuCuxtShH75HPJ6QJ0yac7NRpkK0wpxkZVUrttd3jCIoLX4gDW0CH0p/gfWt/LH/AFAf4mHIQGKk0/hYLwuMMvQdOoPH0ku09Q+MKxVV3ABgQHVXIJomvQ1YvoZndqKMGoLaXcmF9rIpyF2UEAbXNcm+a6gMt0ZsBrTa7IpclNiuBZoncg96NGyPCR7DLLGy9+7XHKWanZlHXZztZdnWmO1Svmbv05P9mdM+Eu0t+JMbOrk492NgCOB8yEHzBuva/SeD7I0CBnx5c2wlFZXYWjjdypANr0HPvDJqcendXVqxklseQOGGPJ7X1B9x/Uy8b03cRlOqarqjmBZpT7L7SXU4w6kX0cD91q/I9RLDsJ09Us3HP02XRmeDYwOo1mNFORnUIP3rFda+8p4u1sWRwmNtxIJtQdooXRPr7SblD0vMYpXZ4otq04qF5r6y7o+0cuntsTlGYBSwCk11rkcf8Smh549OOJLNwB9TMfbPfdu6H4q1inccpcfwuqFa+wE6b8Oa06rAuVl2sxYEC6sHqL8uk41pxx956vsH4rz4CmI7WxICFULTE0as+fJ/kI8c9Zd/B732dN1mqXBjbI37o4UXbN5D+/K5z3tHNgzOubKocI25rLDvchPIJHyooobfcDymT2h8b6jNt3rjDKWAVVYKLHUWTz/f1fsrtxeuXCuRdp2qWIXfVAspsP5nnzMrPLd/C8Ljj5G1mVFUJhfe2U7UxsXrHZO4Ek+Kvced+UAEXMy6XvGXBjX9vlVWfc27lVH1J/D0HMNRqkUHU1+0yB0xoDSY1NgV+B5HvC9ido/qwZnWhW4MRkC5TZaiBwWY1TMOlivWJq3dbZbk1A9aiah8ej06vsxne4IZWfIxq2WrFCl4HJedL7E+GceAd5nG/IxFgjcqk/xAfMeBf7vAPUbj5D4B1+HGz6nUMzajO57tQhfaosFgeTZth9B7z0XaHbTu4KvkRWO3GqpkUt1IvnrX5TTrxxm6x6csrqeHsN8ReYvY3bKZv2fj71LXIWQqpZavm/QibHFe/wBZtjlMpuMssbjdVLdAatEyKEyJvRjVVe00fF6j6jpcFqu0cOMW7oDwAoYF2JNABepnkNZ2jkfICz6hWyHwIjbU4HQANQ4Ejk5scPKsOO5M740+EtinJitsYYvtumQnr04s/wAXnQB5O44HYqYdXjbC6ZTl21gfEppcnUGierUL9ABz5zoh+KcYQJkwuzm0f/DVWugeN3+YcTlup1H6pqXfHvGN3GTHZV22brCk9CL4Pn4RIuWOXfGrmOU8rPZOu2uGyIS+DemXGR/i46KupH8QPP1HvNXXLjxsV7ktjyIOXVCviawEauPK/OwPSea1WvGRzqFFZHffkPygsVAYADysXf8Amm+nxBkxIAgQoaNOgbduFlT7Ec/W5n2nb00t7bq92d2w2ndRk+QLsZmIBfHZ236sKI444PrNft3tPCcdLnxqDvJ2v422qaUVyOavic41XafeOCEC/ugKxC/MT8vl1g9Q55N9b+v0/KEtnZnllj20dtVXVxVUAdxIF/TiXtDqsQdfG/n4rKKhPk3my9L5HnMFRf5Sxpkqz154PlCySI8Ohav4gw6bHjFnIxUUFYPYHBJfz5B/CKeD1WqIRRQ8BIDVbAHnbR8rJPTzMeVLdH1VVwflXrFqW5H0P1sn/iRxv+A6eX0jOwb8h7Sdd9lMe6xp28I/vzMso4Vt1eIGx6X9POUFeq29Pxoxy/X++JNx3U2Xe4lnRTbed37fWH05pPI8N9DKzHcCfIEAt/T85aVXVQdppgNpFVRujXkPC34R3xo+m2C5WORF6AotLxQrpVDpwBzKT+S7uvF2dvr0+/8AOGD1YJAqx6nrAsoIvkMHoDgAihf3uoY7XjcttPQ5lV0Py7GUj0IHlPWfre84aDXjcE+F9p4K1uqvPz9J5DSPe1izM5YDwhQeoAFkcff1mrpu0wcrZP2wQ0uxSu1WujzwKoN+MjLG2tcLY0e0Q5TUMpAA1ALeIHjanH8xIabVaxEdcepxquTa3BYsCEvrXFp1+gmedbuTKMa5KbKWql22wF7gOR8lj6mabdqYjs3DKCthwDhUUAB4fHz5jn0jxmWM7Kure4OE5gyb3xuBlxkBQVK83129KImn2hqmD48gUN3bOGGNt1ECqbgbep9ekyu1O1A5Xu1cIGBBdkR/CST0JH3jvqiVL90fPIxOTcC5NWFIoj8uZNxtuzlk7I9p61ifCCDuL8818tfzW55jX7jW8klQeT5gnp9J6N9Y+a2KIPD3ZU5NviIY2D/pNfQzz7hXB8agBqqyx6HkGueRXTzEvCWemee7Q9+0IF44HNCz0kg5P8uvt/5i7QADAom1OdoskACrFnmwa/GQU0aJAt0W+o54Jv0EdjGyg4x4yT5G4Z6O5fce/B5ElqdMqcoyvydxHlZIU9BVyszGiSa+UV5kfaPW7sXGmwHyllmAoC+noOf7qVRidfFtNAAk8cg3yPXkSYcEkncCoobVBF3x1I/rC47ouPdNiD1/nFAqlg2Re6qs306/SKHSOn8LS6YhGJTotBz03b+tX6EiVdRnDNYCqAAAFAA4FX9Z6DJtP+b7cSI0yfwoP9KzL6882Hcr4jOTC2Rdy0ACqhbA8gLAH1syZ0LcChS9WU8uK6AV7fzmkiKOlD6CoRdo/wDMi8932LqrGOmdQV2bj4dr7jS1z08+DX2h8OkdlstVjaV27moUeOOB/wA+s1Vcf2JLvJN/kX4HVda2zRoHNIT4eGFrwp8wR6n19o+PRuorYT4mNttsr5Aeg4/EzR3xWJP9RkXXZds49msLIs7nFhtvK7evHofylxcO7xEDgEbO7Xbt3X/f1huPSPx6CK8+VHXWe6ZELFDk2uzlwLIbwAAkDrzcA2EbkIGXaN9jZlBG5TzdWfFX4TWD/SP3pEqfyL8F9T0zzuYmxmPA5KOOn1ERB8hn6g9HHINzQOT6yByH1i+tf+oubH1Wjdq2K9iy24gXwAK568S+ezMfpxxxyPX/AIhy99RdcjpwYi/3/CF5srourau/ZuM/a6FtQ+0hk7KRubr2UkCWS5PQkfbmNuMX1M/kdSuezE446G/mbn6yvn7Ls8GhzxYPmfWaBeRLypyZz2rqiinZ+0UbI9z5fb++ZNtHjP7rDm+GP9Yc5Bdecjvj+pn7pyqQ7Px8WXNLTVtstY5HtFLe71ilfUy+T6qlJLFFMaj2VySRRRVF8n/3kx0iiionlMRCKKSdIxXzHigXsyoOT58RzFFA76O39ZBv6xRRQ75MDzGboYopRekBItFFKKF/vIRRRqhm85GKKUr2i0UUUYf/2Q==',
                            'text' => 'Bution',
                            'color' => '#227C9D',
                            'position' => 'vertical',
                            'font' => 'Poppins',
                            'layout' => 'text-image-vertical',
                            'url' => '/',
                        ],
                        'text' => '2022 © Copyright BC Solution. All rights reserved. Hi there!',
                        'socials' => [
                            [
                                'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                'url' => 'https://www.facebook.com/',
                            ],
                            [
                                'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                'url' => '#cardContentOne1',
                            ],
                            [
                                'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                'url' => '/another_page',
                            ],
                            [
                                'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                'url' => '/test',
                            ],
                        ],
                    ],
                    [
                        'type' => 'Form',
                        'title' => 'Contact',
                        'size' => 'large',
                        'inputs' => [
                            [
                                'icon' => [
                                    'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                    'url' => '/',
                                ],
                                'name' => 'company_name',
                                'rules' => [
                                    [
                                        'required' => true,
                                        'message' => 'Please enter your company name',
                                    ],
                                ],
                                'placeholder' => 'Company (*)',
                                'type' => 'text',
                            ],
                            [
                                'icon' => [
                                    'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                    'url' => '/',
                                ],
                                'name' => 'address',
                                'rules' => [
                                    [
                                        'required' => true,
                                        'message' => 'Please enter your address',
                                    ],
                                ],
                                'placeholder' => 'Address (*)',
                                'type' => 'text',
                            ],
                            [
                                'icon' => [
                                    'data' => '<svg fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"/></svg>',
                                    'url' => '/',
                                ],
                                'name' => 'message',
                                'rules' => [
                                    [
                                        'required' => false,
                                        'message' => null,
                                    ],
                                ],
                                'placeholder' => 'Message',
                                'type' => 'textarea',
                            ],
                        ],
                        'button' => [
                            'icon' => '',
                            'text' => 'SEND',
                            'button_type' => 'submit',
                            'type_border' => 'outline',
                            'size' => 'large',
                            'color_background' => '#40A0A0',
                            'color_text' => '#ffffff',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function summary(Request $request) {
        $user = auth('api')->user();
        $mock_quiz_ids = MockQuiz::select('id')->get()->pluck('id');
        return [
          'is_existed' => (boolean)$user,
          'number_lesson' => Lesson::count(),
          'number_question' => Question::count(),
          'number_mock_quiz_passed' => $user ? ExaminationMockQuiz::where('user_id', $user->id)->where('state', ExaminationStatus::Pass)->whereIn('quiz_id', $mock_quiz_ids)->count() : null,
          'number_lesson_complete' => $user?->lessons()->wherePivot('is_complete', true)->count(),
          'number_question_complete' => $user?->questions()->wherePivot('is_correct', true)->count(),
          'number_mock_quiz_complete' => $user ? ExaminationMockQuiz::where('user_id', $user->id)->whereIn('quiz_id', $mock_quiz_ids)->count() : null,
          'total_mock_quiz' => MockQuiz::count(),
          'username' => $user?->name,
          'avatar' => $user?->avatar_url,
        ];
    }
}
