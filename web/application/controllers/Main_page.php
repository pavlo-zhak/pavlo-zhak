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
        App::get_ci()->load->model('Boosterpack_model');
        App::get_ci()->load->model('Analytics_model');

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
        $replay_to = App::get_ci()->input->post('replay_to');

        if (empty($post_id) || empty($comment_text)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        if(!$replay_to) $replay_to = null;

        try
        {
            $inserted_comment_id = Comment_model::create([
                'user_id' => User_model::get_session_id(),
                'assign_id' => $post_id,
                'text' => $comment_text,
                'reply_id' => $replay_to,
            ]);

        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

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

        // Find user by email
        $user = User_model::find_user(['email' => $login_params_from_client->login]);
        // If password check is success - authorize user
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
        // Check user is authorize
        if (!User_model::is_logged()) return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        // Get sum from client
        $sum = App::get_ci()->input->post('sum');
        if(!$sum) return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);

        // Add sum to user wallet balance
        $user = User_model::get_user();
        $user->set_wallet_balance(
          $user->get_wallet_balance() + $sum
        );
        $user->set_wallet_total_refilled(
            $user->get_wallet_total_refilled() + $sum
        );

        Analytics_model::create([
            'user_id' => $user->get_id(),
            'object' => 'wallet',
            'action' => 'replenishment',
            'amount' => $sum
        ]);

        return $this->response_success(['amount' => $user->get_wallet_balance()]);
    }

    public function buy_boosterpack(){
        // Check user is authorize
        if (!User_model::is_logged()) return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        // Get booster pack Id and load instance
        $boosterpack_id = App::get_ci()->input->post('id');
        if(!$boosterpack_id) return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        $boosterpack = new Boosterpack_model($boosterpack_id);

        // Check user wallet balance
        $user = User_model::get_user();
        if($user->get_wallet_balance() < $boosterpack->get_price()) return $this->response_error('not enough money on balance');

        // Open booster pack and return likes count
        $likes_from_booster_pack = Boosterpack_model::open_booster_pack($boosterpack_id);

        return $this->response_success(['amount' => $likes_from_booster_pack]);
    }


    public function like($object_type, $object_id){
        // Check user is authorize
        if (!User_model::is_logged()) return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        // object_type - is a parameter that describes the type of object to which likes should be added (comment or post)
        $likes = null;
        if(!$object_type || !$object_id) return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        $user = User_model::get_user();
        // Check user "likes" balance
        if ($user->get_likes_balance() == 0) return $this->response_error('no likes on balance');
        switch ($object_type)
        {
            case 'post':
                $post = new Post_model($object_id);
                $likes = $post->increment_post_likes();
                break;
            case 'comment':
                $comment = new Comment_model($object_id);
                $likes = $comment->increment_comment_likes();
                break;
            default:
                throw new Exception('undefined object type');
        }

        return $this->response_success(['likes' => $likes]); // Колво лайков под постом \ комментарием чтобы обновить
    }

}
