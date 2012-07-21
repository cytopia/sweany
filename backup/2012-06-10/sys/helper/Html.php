<?php

define('BR', '<br/>');

Class Html
{
	/**
	 *
	 * Build internal <a href></a> construct
	 *
	 * If controller, method or params are null it will
	 * get the current controller, method or params
	 * @param String $name
	 * @param String $controller
	 * @param String $method
	 * @param Array $params
	 * @param Array $attributes
	 * @param String $anchor
	 */
	public static function l($name, $controller = null, $method = null, $params = array(), $attributes = array(), $anchor = null)
	{
		if (is_null($controller))
			$controller	= Url::getController();

		if (is_null($method))
			$method	= Url::getMethod();


		// TODO: maybe need to escape the params for url - keep an eye on it
		$args = implode(DS,  array_map(create_function('$param', 'return ($param);'), array_values($params)));
		$attr = implode(' ', array_map(create_function('$key, $val', 'return $key."=\"".$val."\"";'), array_keys($attributes), array_values($attributes)));
		$link = DS.$controller.DS.$method.DS.$args;

		return '<a href="'.$link.'"'.$attr.'>'.$name.'</a>';
	}

	/**
	 *
	 * Build external <a href></a> construct
	 * @param Stromg $name
	 * @param String $link
	 * @param Array $attributes
	 */
	public static function el($name, $link, $attributes = array())
	{
		$attr	= implode(' ', array_map( create_function('$key, $val', 'return $key."=\"".$val."\"";'), array_keys($attributes), array_values($attributes)));

		return '<a '.$attr.' href="'.$link.'">'.$name.'</a>';
	}

/*	public static function l($controller = NULL, $method = NULL, $name, $params = NULL, $options = NULL)
	{
		if (is_null($controller))
			$controller	= Url::getController();

		if (is_null($method))
			$method	= Url::getMethod();

		$args = '';
		$opts = '';

		if (is_array($params))
			$args = implode(DS, array_map( create_function('$param', 'return ($param);'), array_values($params)));

		if ( is_array($options) )
		{
			foreach ($options as $key => $value)
			{
				$opts	.= $key.'="'.$value.'" ';
			}
		}

		$link = DS.$controller.DS.$method.DS.$args;
		$href = '<a title="'.$name.'" '.$opts.' href="'.$link.'">'.$name.'</a>';
		return $href;
	}*/

	// vertical bar chart
	public static function bar_h($percentage, $length=25)
	{
		return ( self::img('/img/charts/bar_horizontal.php?side='.$length.'&percent='.$percentage, $percentage.' Prozent') );
	}


	// <p>
	public function p($text)
	{
		return '<p>'.$text.'</p>';
	}

	// <br>
	public function br($num)
	{
		$br = '';
		for ($i=0; $i<$num; $i++)
			$br .= '<br/>';

		return $br;
	}

	// <img>
	public static function img($src, $alt = NULL, $options = null)
	{
		$opts = '';
		if ( is_array($options) )
		{
			foreach ($options as $key => $value)
			{
				$opts	.= $key.'="'.$value.'" ';
			}
		}
		return '<img border="0" src="'.$src.'" alt="'.$alt.'"' .$opts.' />';
	}



	/******************************************************** T A B L E ********************************************************/
	public function table()
	{
		$table	= '<table>';
		$args	= func_get_args();

		for ($i=0; $i<func_num_args(); $i++)
			$table .= $args[$i];

		return $table.'</table>';
	}


	public function tr()
	{
		$tr		= '<tr>';
		$args	= func_get_args();

		for ($i=0; $i<func_num_args(); $i++)
			$tr .= $args[$i];

		return $tr.'</tr>';
	}


	public function th($content)
	{
		return '<th>'.$content.'</th>';
	}

	public function td($content)
	{
		return '<td>'.$content.'</td>';
	}





	public function pagerLinks($page_param_pos, $left_page = NULL, $right_page = NULL, $betweenPages = array())
	{
		$string		= '';
		$curr_page	= 0;

		if (is_numeric($left_page))
		{
			$string		= $this->pagerLink($page_param_pos, $left_page, 'left').' ';
			$curr_page	= $left_page+1;
		}
		if (is_numeric($right_page))
		{
			$curr_page	= $right_page-1;
		}

		foreach ($betweenPages as $page)
		{
			if ( $page == $curr_page)
			{
				$string .= $this->pagerLink($page_param_pos, $page, 'current').' ';
			}
			else
				$string .= $this->pagerLink($page_param_pos, $page, 'between').' ';
		}
		if (is_numeric($right_page))
		{
			$string		.= $this->pagerLink($page_param_pos, $right_page, 'right').' ';
		}

		return $string;
	}


	private function pagerLink($page_param_pos, $page, $type)
	{
		$controller = Url::getController();
		$method		= Url::getMethod();
		$params		= Url::getParams();
		$name		= '';

		switch ($type)
		{
			case 'left':	$name = 'prev';	break;
			case 'right':	$name = 'next';	break;
			case 'current':	$name = '<strong>('.$page.')</strong>'; break;
			default:		$name = $page;	break;
		}

		for ($i=0; $i<$page_param_pos; $i++)
		{
			$params[$i] = (isset($params[$i]) && strlen($params[$i])) ? $params[$i] : 0;
		}

		$params[$page_param_pos-1] = $page;

		return $this->l($controller, $method, $name, $params);
	}
}

?>