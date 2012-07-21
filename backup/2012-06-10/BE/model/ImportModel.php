<?php

class ImportModel extends ControllerModel
{


	public function importUsbIds($file_name)
	{
		$fp			= fopen($file_name, 'r');
		$part		= 0;
		$data		= array();
		$tmp		= array();
		$devices	= array();

		while ( ($buffer = fgets($fp,1000)) !== false )
		{
			if ( !strlen(trim($buffer)) )
				$part++;
			else if ( $buffer[0] == '#' )
			{}
			else
				$tmp[$part][] = $buffer;

		}
		foreach ($tmp as $t)
		{
			if (sizeof($t)>0)
			{
				$data[] = $t;
			}

		}
		fclose($fp);
		// $data[0] ven/dev
		// $data[1] class

		$vendor_id		= '';
		$vendor_name	= '';
/*
		foreach ($data[0] as $devs)
		{
			$tmp = str_getcsv($devs, "\t");
			
			//---- vendor
			if ( sizeof($tmp) == 1 )
			{
				$tmp2 			= explode("  ", $tmp[0]);
				$vendor_id		= $tmp2[0];
				$vendor_name	= $tmp2[1];
				MySql::insertRow('usb_db_vendors', array('vendor_id' => $vendor_id, 'name' => $vendor_name));
			}
			else if ( sizeof($tmp) == 2 )
			{
				$tmp2 			= explode("  ", $tmp[1]);
				$device_id		= $tmp2[0];
				$device_name	= $tmp2[1];
				MySql::insertRow('usb_db_devices', array('vendor_id' => $vendor_id, 'device_id' => $device_id, 'name' => $device_name));
			}
			else
			{
				debug($tmp);
			}
		}
*/		
		$class;
		$subclass;
		$protocol;
		foreach ($data[1] as $classes)
		{
			$tmp = str_getcsv($classes, "\t");

			if ( sizeof($tmp) == 1 )
			{
				$tmp2			= explode("  ", $tmp[0]);
				$tmp3			= explode(" ", $tmp2[0]);

				$class			= $tmp3[1];
				$class_name		= $tmp2[1];
				MySql::insertRow('usb_db_classes', array('class' => $class, 'name' => $class_name, 'is_subclass' => 0, 'is_protocol' => 0));
			}
			else if ( sizeof($tmp) == 2 )
			{
				$tmp2			= explode("  ", $tmp[1]);
				$subclass		= $tmp2[0];
				$subclass_name	= $tmp2[1];
				MySql::insertRow('usb_db_classes', array('class' => $class, 'subclass' =>$subclass, 'name' => $subclass_name, 'is_subclass' => 1, 'is_protocol' => 0));
			}
			else if ( sizeof($tmp) == 3 )
			{
				$tmp2			= explode("  ", $tmp[2]);
				$protocol		= $tmp2[0];
				$protocol_name	= $tmp2[1];
				MySql::insertRow('usb_db_classes', array('class' => $class, 'subclass' =>$subclass, 'protocol' => $protocol, 'name' => $protocol_name, 'is_subclass' => 0, 'is_protocol' => 1));
			}
			else
			{
				debug($tmp);
			}
		}


	}

}