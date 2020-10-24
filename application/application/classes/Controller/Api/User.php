<?php

/**
 * Class Controller_Api_User
 */
class Controller_Api_User extends Controller
{
    /**
     *
     */
    public function action_all()
    {
        /** @var Model_User $users */
        $users = ORM::factory('User')->all_users();

        if ($users === null) {
            $response = [
                'status' => 'FAIL'
            ];
        } else {
            $response = [
                'status' => 'SUCCESS',
                'data' => $users
            ];
        }

        $this->response->body(
            json_encode($response)
        );
    }

    /**
     *
     */
    public function action_index()
    {
        if ($this->request->method() === Request::GET) {
            return $this->action_get();
        }

        if ($this->request->method() === Request::PUT) {
            return $this->action_update();
        }

        if ($this->request->method() === Request::POST) {
            return $this->action_create();
        }

        if ($this->request->method() === Request::DELETE) {
            return $this->action_delete();
        }
    }

    /**
     *
     */
    public function action_get()
    {
        $user = ORM::factory('User')->one_user($this->request->param('id', null));

        if ($user === null) {
            $response = [
                'status' => 'FAIL',
                'message' => 'Юзер не найден'
            ];
        } else {
            $response = [
                'status' => 'SUCCESS',
                'data' => $user
            ];
        }

        $this->response->body(
            json_encode($response)
        );
    }

    /**
     *
     */
    public function action_create()
    {
        $name = $this->request->post('name', null);
        $city = $this->request->post('city', null);
        $url = $this->request->post('url', null);

        $response = [
            'status' => 'FAIL'
        ];

        $parsed_url = parse_url($url);
        $domain = $parsed_url['scheme'] . '://' . $parsed_url['host'];
        $path = $parsed_url['path'];
        $validation = $this->validation_user($name, $city, $domain);

        if ($validation) {
            $user = ORM::factory('User')->new_user($name, $city, $domain, $path);

            if ($user instanceof Model_User) {
                $response = [
                    'status' => 'SUCCESS'
                ];

                $client = new GearmanClient();
                $client->addServer('gearman');
                $client->doBackground("counter", serialize(null));
            } else {
                $response['message'] = 'Ошибка сохранения';
            }
        } else {
            $response['message'] = 'Вы ввели не валидные данные';
        }

        $this->response->body(
            json_encode($response)
        );
    }

    /**
     *
     */
    public function action_update()
    {
        $id = $this->request->param('id', null);
        parse_str($this->request->body(), $body);

        $response = [
            'status' => 'FAIL'
        ];

        $parsed_url = parse_url($body['url']);
        $domain = $parsed_url['scheme'] . '://' . $parsed_url['host'];
        $path = $parsed_url['path'];
        $validation = $this->validation_user($body['name'], $body['city'], $domain);

        if ($validation) {
            $user = ORM::factory('User')->update_user($id, $body['name'], $body['city'], $domain, $path);

            if ($user !== false) {
                $response = [
                    'status' => 'SUCCESS'
                ];

                $client = new GearmanClient();
                $client->addServer('gearman');
                $client->doBackground("counter", serialize(null));
            } else {
                $response['message'] = 'Ошибка сохранения. Юзер не найден';
            }
        } else {
            $response['message'] = 'Вы ввели не валидные данные';
        }

        $this->response->body(
            json_encode($response)
        );
    }

    /**
     *
     */
    public function action_delete()
    {
        $id = $this->request->param('id', null);

        $response = [
            'status' => 'FAIL'
        ];

        $result = ORM::factory('User')->remove_user($id);

        if ($result) {
            $response = [
                'status' => 'SUCCESS'
            ];
        }

        $this->response->body(
            json_encode($response)
        );
    }

    /**
     *
     * TODO:Перенести в отдельный класс с валидацией
     */
    private function validation_user(string $name, string $city, string $url) : bool
    {
        return Validation::factory(['name' => $name,'city' => $city, 'domain' => $url])
            ->rule('name', 'not_empty')
            ->rule('city', 'not_empty')
            ->rule('domain', 'url')
            ->check();
    }
}
