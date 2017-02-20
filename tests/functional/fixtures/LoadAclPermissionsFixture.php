<?php
use \DB;

class LoadAclPermissionsFixture
{
    public function run($db)
    {
        try {

            $db->table('acl_permissions')->insert([
                [
                    'id' => 321,
                    'slug' => 'a_permission',
                    'title' => 'A Permission',
                    'description' => 'This is a permission',
                    'created_at' => '2016-11-28 00:00:00',
                    'updated_at' => '2016-11-28 00:00:00',
                    'deleted' => false
                ],
                [
                    'id' => 323,
                    'slug' => 'b_permission',
                    'title' => 'B Permission',
                    'description' => 'This is not a permission, it\'s b permission. Get it? Get it? It\'s funny.... These are the jokes, people.',
                    'created_at' => '2016-11-28 00:00:00',
                    'updated_at' => '2016-11-28 21:00:00',
                    'deleted' => false
                ],
                [
                    'id' => 344,
                    'slug' => 'c_permission',
                    'title' => 'C Permission',
                    'description' => 'Yes, I C the permission.',
                    'created_at' => '2016-11-28 00:00:00',
                    'updated_at' => '2016-11-29 02:00:00',
                    'deleted' => true
                ],
                [
                    'id' => 665,
                    'slug' => 'd_permission',
                    'title' => 'D Permission',
                    'description' => 'This is d permission.',
                    'created_at' => '2016-11-28 00:00:00',
                    'updated_at' => '2016-11-28 00:00:00',
                    'deleted' => false
                ]
            ]);
             
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit;
        }
         
    }
}