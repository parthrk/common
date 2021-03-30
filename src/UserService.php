<?php

namespace Microservices;


use Gate;
use Http;

class UserService
{

    private $end_point;

    public function __construct(){
        $this->end_point = env('USERS_ENDPOINT');
    }

    public function headers()
    {
        return [
            'Authorization' => request()->headers->get('Authorization'),
        ];
    }

    public function request()
    {
        return Http::withHeaders($this->headers());
    }

    public function getUser()
    {
        $json = $this->request()->get("{$this->end_point}/user")->json();

        $user = new User($json);

        return $user;
    }

    public function isAdmin()
    {
        return $this->request()->get("{$this->end_point}/admin")->successful();
    }

    public function isInfluencer()
    {
        return $this->request()->get("{$this->end_point}/influencer")->successful();
    }

    public function allows($ability, $arguments)
    {
        return Gate::forUser($this->getUser())->authorize($ability, $arguments);
    }

    public function all($page)
    {
        return $this->request()->get("{$this->end_point}/users?page={$page}")->json();
    }

    public function get($id)
    {
        $json = $this->request()->get("{$this->end_point}/user/{$id}")->json();

        return new User($json);
    }

    public function create($data)
    {
        $json = $this->request()->post("{$this->end_point}/users", $data)->json();

        return new User($json);
    }

    public function update($id, $data)
    {
        $json = $this->request()->put("{$this->end_point}/users/{$id}", $data)->json();

        return new User($json);
    }

    public function delete($id)
    {
        return $this->request()->delete("{$this->end_point}/users/{$id}")->successful();
    }
}
