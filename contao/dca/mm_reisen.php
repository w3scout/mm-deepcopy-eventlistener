<?php
// contao/dca/mm_parent.php

$GLOBALS['TL_DCA']['mm_reisen'] = [

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
                'source' => 'mm_reisen'
            ],
            'mm_uebersicht_bausteine' => [
                'source' => 'mm_uebersicht_bausteine'
            ],
            'mm_reiseverlauf' => [
                'source' => 'mm_reiseverlauf'
            ],
            'mm_reiseverlauf_artikelbausteine' => [
                'source' => 'mm_reiseverlauf_artikelbausteine'
            ],
            'mm_reiseverlauf_orte' => [
                'source' => 'mm_reiseverlauf_orte'
            ],
            'mm_arrangement_bausteine' => [
                'source' => 'mm_arrangement_bausteine'
            ],
            'mm_wissenswert_bausteine' => [
                'source' => 'mm_wissenswert_bausteine'
            ],
            'mm_termine' => [
                'source' => 'mm_termine'
            ],
            'mm_preise' => [
                'source' => 'mm_preise'
            ]
        ],
        'childCondition' => [
            [
                'from' => 'mm_reisen',
                'to' => 'mm_uebersicht_bausteine',
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
                'from' => 'mm_reisen',
                'to' => 'mm_reiseverlauf',
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
            ],

            [
                'from' => 'mm_reisen',
                'to' => 'mm_arrangement_bausteine',
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
                'from' => 'mm_reisen',
                'to' => 'mm_wissenswert_bausteine',
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
                'from' => 'mm_reisen',
                'to' => 'mm_termine',
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
                'from' => 'mm_reisen',
                'to' => 'mm_preise',
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
