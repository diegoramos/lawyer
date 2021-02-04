<?php

class MY_Controller extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	// Lazy load models + libraries....If we can't load a model that we have; then we will try to load library $name
	public function __get($name)
	{
		//Cache models so we only scan model dir once

		static $models = FALSE;
		$this->load->helper('file');

		if (!$models)
		{
			$model_files = get_filenames(APPPATH.'models', TRUE);
			foreach($model_files as $model_file)
			{
				$model_relative_name = str_replace('.php','',substr($model_file,strlen(APPPATH.'models'.DIRECTORY_SEPARATOR)));
				$model_folder = strpos($model_relative_name, DIRECTORY_SEPARATOR) !== FALSE ? substr($model_relative_name,0,strrpos($model_relative_name,DIRECTORY_SEPARATOR)) : '';
				$model_name = str_replace($model_folder.DIRECTORY_SEPARATOR, '',$model_relative_name);

				$models[$model_name] = $model_folder.'/'.$model_name;
			}
		}

		if (isset($models[$name]))
		{
			$this->load->model($models[$name]);
			$log_message = "Lazy Loaded model $name CURRENT_URL: ".current_url().' REQUEST '.var_export($_REQUEST, TRUE);
			log_message('error', $log_message);
			return $this->$name;
		}
		else //Try a library if we cannot load a model
		{
			$this->load->library($name);
			$log_message = "Lazy Loaded library $name CURRENT_URL: ".current_url().' REQUEST '.var_export($_REQUEST, TRUE);
			log_message('error', $log_message);
			return $this->$name;
		}

		return NULL;
	}
}