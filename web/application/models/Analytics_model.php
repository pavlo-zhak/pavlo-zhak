<?php

class Analytics_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'analytics';

    /** @var Int */
    protected $user_id;
    /** @var String describes the object with which it is carried interaction (boosterpack, wallet, etc)  */
    protected $object;
    /** @var Int action with object (buy boosterpack, add money to wallet ...) */
    protected $action;
    /** @var Int object id with which user interaction */
    protected $object_id;
    /** @var Int */
    protected $amount;
    /** @var Int */
    protected $time_created;
    /** @var Int */
    protected $time_updated;

    /**
     * @return int
     */
    public function get_user_id(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return bool
     */
    public function set_user_id(int $user_id)
    {
        $this->user_id = $user_id;
        return $this->save('user_id', $user_id);
    }

    /**
     * @return String
     */
    public function get_object()
    {
        return $this->object;
    }

    /**
     * @param $object
     * @return bool
     */
    public function set_object($object)
    {
        $this->object = $object;
        return $this->save('object', $object);
    }

    /**
     * @return Int
     */
    public function get_action()
    {
        return $this->action;
    }

    /**
     * @param $action
     * @return bool
     */
    public function set_action($action)
    {
        $this->action = $action;
        return $this->save('action', $action);
    }

    /**
     * @return Int
     */
    public function get_object_id()
    {
        return $this->object_id;
    }

    /**
     * @param $object_id
     * @return bool
     */
    public function set_object_id($object_id)
    {
        $this->object_id = $object_id;
        return $this->save('object_id', $object_id);
    }

    /**
     * @return int
     */
    public function get_amount(): Int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return bool
     */
    public function set_amount(int $amount)
    {
        $this->amount = $amount;
        return $this->save('amount', $amount);
    }

    /**
     * @return string
     */
    public function get_time_created(): string
    {
        return $this->time_created;
    }

    /**
     * @param string $time_created
     *
     * @return bool
     */
    public function set_time_created(string $time_created)
    {
        $this->time_created = $time_created;
        return $this->save('time_created', $time_created);
    }

    /**
     * @return string
     */
    public function get_time_updated(): string
    {
        return $this->time_updated;
    }

    /**
     * @param string $time_updated
     *
     * @return bool
     */
    public function set_time_updated(string $time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->save('time_updated', $time_updated);
    }

    function __construct($id = NULL)
    {
        parent::__construct();

        $this->set_id($id);
    }

    public function reload(bool $for_update = FALSE)
    {
        parent::reload($for_update);

        return $this;
    }

    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

    public function delete()
    {
        $this->is_loaded(TRUE);
        App::get_ci()->s->from(self::CLASS_TABLE)->where(['id' => $this->get_id()])->delete()->execute();
        return (App::get_ci()->s->get_affected_rows() > 0);
    }

    public function get_analytics_for_user($user_id)
    {
        $data = App::get_ci()->s->from(self::CLASS_TABLE)->where(['user_id' => $user_id])->orderBy('time_created','ASC')->many();
        $result = [];
        foreach ($data as $i)
        {
            $result[] = (new self())->set($i);
        }
        return $result;
    }

}
