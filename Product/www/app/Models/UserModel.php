<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model {

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $DBGroup = 'default';
    protected $allowedFields = ['id', 'oauth_id', 'username', 'first_name', 'last_name', 'email', 'profile_img', 'created_at', 'updated_at', 'classID_1', 'classID_2', 'classID_3', 'classID_4', 'classID_5', 'classID_6'];

    public function userConfiguration($id)
    {
        //find user record
        $user = $this->find($id);

        // identify user type
        if ($user['last_name'] == '[S]') {
            $userType = 'student';
        } elseif ($user['last_name'] == '[T]') {
            $userType = 'teacher';
        } elseif ($user['last_name'] == '[A]') {
            $userType = 'admin';
        }

        // list of classIDs for the user
        $classIDs = [
            $user['classID_1'],
            $user['classID_2'],
            $user['classID_3'],
            $user['classID_4'],
            $user['classID_5'],
            $user['classID_6'],
        ];

        return [$userType, $classIDs];
    }

    public function findUsername($ids)
    {
        //find username by id
        $usernames = [];
        foreach ($ids as $id) {
            $usernames[] = $this->find($id)['username'];
        }

        return $usernames;
    }

    public function findUsernames_attachedToIds($ids)
    {
        //find username by id
        $usernames = [];
        foreach ($ids as $id) {
            $usernames[] = $this->find($id)['username'];
        }

        // output ids as the key, usernames as the value
        return array_combine($ids, $usernames);
    }

        
}