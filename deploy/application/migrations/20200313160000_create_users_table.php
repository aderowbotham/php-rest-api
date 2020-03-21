<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users_table extends CI_Migration {

  /*
  @IMPORTANT
  This migration creates a user *just for demo purposes* of the permissions system
  The username is test@example.com and their access key is a sha256 hash of their username + $config['public_key_salt'
  **You should remove this user and add your own user(s) via your own mechanism.**
  Note that in this schema we use PHP password_hash https://www.php.net/manual/en/function.password-hash.php
  This creates a hash that requires 72 characters to store
  */

  private static $demo_username = 'test@example.com';
  private static $demo_secret_key = 'DEMO_ONLY_CHANGE_ME_5678@';

  public function up()
  {

    echo "Running <b>Migration_Create_users_table</b> \n";

    $this->dbforge->add_field('id');
    $this->dbforge->add_field([
      'username' => [
        'type' => 'VARCHAR',
        'constraint' => 100,
        'unsigned' => true,
        'null' => false
      ],
      'access_key' => [
        'type' => 'CHAR',
        'constraint' => 64,
        'unsigned' => true,
        'null' => false
      ],
      'secret_key_hash' => [
        'type' => 'CHAR',
        'constraint' => 72,
        'unsigned' => true,
        'null' => false
      ],
      'permissions' => [
        'type' => 'TINYINT',
        'constraint' => '1',
        'default' => USER_PUBLIC,
        'null' => false,
        'unsigned' => true
      ],
      'enabled' => [
        'type' => 'TINYINT',
        'constraint' => '1',
        'default' => 1,
        'null' => false,
        'unsigned' => true
      ],
      'failed_attempts' => [
        'type' => 'TINYINT',
        'constraint' => '1',
        'default' => 0,
        'null' => false,
        'unsigned' => true
      ],
      'created_at' => [
        'type' => 'datetime',
        'null' => false
      ],
      'updated_at' => [
        'type' => 'datetime',
        'null' => false
      ],
    ]);


    $this->dbforge->add_key('access_key', TRUE);
    $this->dbforge->create_table('api_users');

    $this->db->query("ALTER TABLE `api_users` ADD UNIQUE INDEX `username` (`username`);");

    $this->load->helper('api_helper');

    // insert demo user
    $demo_user = [
      'username' => self::$demo_username,
      'access_key' => make_access_key(self::$demo_username, $this->config->item('public_key_salt')),
      'secret_key_hash' => make_secret_key_hash(self::$demo_secret_key),
      'permissions' => USER_ADMIN,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];

    $this->db->insert('api_users', $demo_user);
    echo "Demo user created\n";
  }

  public function down()
  {

    echo "Reversing <b>Migration_Create_users_table</b> \n";
    $this->dbforge->drop_table('api_users');
  }
}
