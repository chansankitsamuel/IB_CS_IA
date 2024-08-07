<?php

/**
 * This file is part of Shield OAuth.
 *
 * (c) Datamweb <pooya_parsa_dadashi@yahoo.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Datamweb\ShieldOAuth\Database\Migrations;

use CodeIgniter\Database\Migration;

class ShieldOAuth extends Migration
{
    public function __construct()
    {
        parent::__construct();

        $this->first_name = config('ShieldOAuthConfig')->usersColumnsName['first_name'];
        $this->last_name  = config('ShieldOAuthConfig')->usersColumnsName['last_name'];
        $this->avatar     = config('ShieldOAuthConfig')->usersColumnsName['avatar'];
    }

    public function up()
    {
        $fields = [
            $this->first_name => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            $this->last_name => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            $this->avatar => [
                'type'       => 'VARCHAR',
                'constraint' => '1000',
                'null'       => true,
            ],

        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = [
            $this->first_name,
            $this->last_name,
            $this->avatar,
        ];

        $this->forge->dropColumn('users', $fields);
    }
}
