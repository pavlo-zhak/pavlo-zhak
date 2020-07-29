<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');
        App::get_ci()->load->model('Login_model');
        App::get_ci()->load->model('Post_model');

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();



        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_post($post_id){ // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS

        $post_id = intval($post_id);

        if (empty($post_id)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }


        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }


    public function comment(){ // or can be App::get_ci()->input->post('news_id') , but better for GET REQUEST USE THIS ( tests )

        if (!User_model::is_logged()){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        $post_id = App::get_ci()->input->post('postId');
        $comment_text = App::get_ci()->input->post('commentText');

        if (empty($post_id) || empty($comment_text)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $inserted_comment_id = Comment_model::create([
                'user_id' => User_model::get_session_id(),
                'assign_id' => $post_id,
                'text' => $comment_text,
            ]);

        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }


//        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['comment_id' => $inserted_comment_id]);
    }


    public function login()
    {
        // Get login and password from client side
        $login_params_from_client = new stdClass();
        $login_params_from_client->login = App::get_ci()->input->post("login");
        $login_params_from_client->password = App::get_ci()->input->post("password");

        // login or password cannot be empty
        if(!$login_params_from_client->login || !$login_params_from_client->password) return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);

        $user = User_model::find_user(['email' => $login_params_from_client->login]);
        if($user->get_password() !== $login_params_from_client->password) return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);

        Login_model::start_session($user->get_id());

        return $this->response_success(['user' => $user->get_id()]);
    }


    public function logout()
    {
        Login_model::logout();
        redirect(site_url('/'));
    }

    public function add_money(){
        // todo: add money to user logic
        return $this->response_success(['amount' => rand(1,55)]);
    }

    public function buy_boosterpack(){
        // todo: add money to user logic
        return $this->response_success(['amount' => rand(1,55)]);
    }


    public function like(){
        // todo: add like post\comment logic
        return $this->response_success(['likes' => rand(1,55)]); // Колво лайков под постом \ комментарием чтобы обновить
    }

}
