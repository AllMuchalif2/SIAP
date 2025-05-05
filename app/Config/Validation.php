<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $siswa = [
            'id_siswa' => 'permit_empty|is_unique[siswa.id_siswa]',
            'nama' => 'required',
            'sekolah' => 'required',
            'status' => 'required',
        ];

    public $siswa_errors = [
            'id_siswa' => [
                'is_unique' => 'id siswa sudah ada',
            ],
            'nama' => [
                'required' => 'Nama harus diisi',
            ],
            'sekolah' => [
                'required' => 'Sekolah harus diisi',
            ],
            'status' => [
                'required' => 'Status harus diisi!',
            ]
        ];
}
