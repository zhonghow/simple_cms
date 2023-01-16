<?php

class Post
{
    public static function getAllPosts()
    {
        return Database::connectDatabase()->selectData(
            'SELECT * FROM posts ORDER BY id DESC',
            [],
            true
        );
    }

    public static function getPostID($post_id)
    {
        return Database::connectDatabase()->selectData(
            'SELECT * FROM posts WHERE id = :id',
            [
                'id' => $post_id
            ],
        );
    }

    public static function updatePost($id, $title, $content, $status)
    {
        // Setup Params
        $params = [
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'status' => $status
        ];

        // Update post data into database
        return Database::connectDatabase()->updateData(
            'UPDATE posts SET title = :title, content = :content, status = :status WHERE id = :id',
            $params
        );
    }

    public static function addPost($content, $title, $user_id)
    {
        return Database::connectDatabase()->insertData(
            'INSERT INTO posts (content, title, user_id) VALUES (:content, :title, :user_id)',
            [
                'content' => $content,
                'title' => $title,
                'user_id' => $user_id
            ]
        );
    }

    public static function deletePost($id)
    {
        Database::connectDatabase()->deleteData(
            'DELETE FROM posts WHERE id = :id',
            [
                'id' => $id
            ]
        );
    }

    public static function getPublishedPost()
    {
        return Database::connectDatabase()->selectData(
            'SELECT * FROM posts WHERE status = :status ORDER BY id DESC',
            [
                'status' => 'publish'
            ],
            true
        );
    }
}
