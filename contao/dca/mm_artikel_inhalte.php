<?php
// contao/dca/mm_parent.php

/*
$GLOBALS['TL_DCA']['mm_artikel_inhalte'] = [

    // Config
    'config' => array
    (
        // Replace the data container Table with General.
        'dataContainer' => 'General',
        // wird erst ab DCG 2.2 untertützt
        'notCopyable' => false,
        // wird erst ab DCG 2.2 untertützt
        'enableVersioning' => false,
        'oncopy_callback' => array
        (
            //array('mm_reisen', 'copyChilds')
        )
        // *_callback per Event
    ),

    'dca_config' => [
        'data_provider' => [
            'default' => [
                'source' => 'mm_artikel_inhalte'
            ]
        ],
        'childCondition' => [
            [
                'from' => 'mm_reiseverlauf',
                'to' => 'mm_reiseverlauf_artikelbausteine',
                'setOn' => [
                    [
                        'to_field' => 'pid',
                        'from_field' => 'id',
                    ],
                ],
                'filter' => [
                    [
                        'local' => 'pid',
                        'remote' => 'id',
                        'operation' => '=',
                    ],
                ],
                'inverse' => [
                    [
                        'local' => 'pid',
                        'remote' => 'id',
                        'operation' => '=',
                    ],
                ]
            ],
            [
                'from' => 'mm_reiseverlauf',
                'to' => 'mm_reiseverlauf_orte',
                'setOn' => [
                    [
                        'to_field' => 'pid',
                        'from_field' => 'id',
                    ],
                ],
                'filter' => [
                    [
                        'local' => 'pid',
                        'remote' => 'id',
                        'operation' => '=',
                    ],
                ],
                'inverse' => [
                    [
                        'local' => 'pid',
                        'remote' => 'id',
                        'operation' => '=',
                    ],
                ]
            ]
        ]
    ]
];
*/
