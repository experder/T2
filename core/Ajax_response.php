<?php

namespace t2\core;


class Ajax_response {

	const TYPE_JSON = 1;
	const TYPE_HTML = 2;
	const TYPE_ERROR = 3;

	private $type;
	private $ok;
	private $error_id = null;
	private $error_msg = null;
	private $data = array();
	private $html = "";

	/**
	 * Ajax_response constructor.
	 * @param int          $type
	 * @param array|string $data
	 * @param bool         $compile_and_send
	 * @param string       $error_id
	 */
	public function __construct($type, $data, $error_id=null, $compile_and_send=true) {
		$this->type = $type;

		switch ($type){
			case self::TYPE_JSON:
				$this->ok = true;
				$this->data = $data;
				break;
			case self::TYPE_HTML:
				$this->ok = true;
				$this->html = $data;
				break;
			case self::TYPE_ERROR:
				$this->ok = false;
				$this->error_msg = $data;
				$this->error_id = $error_id;
				break;
			default:
				break;
		}
		if($compile_and_send){
			$this->compile_and_send();
		}
	}

	public function compile_and_send(){
		$response = array(
			"ok" => $this->ok,
		);
		if($this->ok){
			if($this->type==self::TYPE_JSON){
				$response['data'] = $this->data;
			}
			if($this->type==self::TYPE_HTML){
				$response['html'] = $this->html;
			}
		}else{
			$response['error_id'] = $this->error_id;
			$response['error_msg'] = $this->error_msg;
		}
		$response_json = json_encode($response);
		echo $response_json;
		exit;
	}

}