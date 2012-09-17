<?php

class Heystack_SS_Report extends SS_Report
{
    
    function parameterFields()
	{
		$params = new FieldSet();
		$start = new DateField('StartDate','Start Date');
		$start->setConfig('showcalendar',true);
		$start->setConfig('dateformat', 'dd/MM/YYYY'); 

		$end = new DateField('EndDate','End Date');
		$end->setConfig('showcalendar',true);
		$end->setConfig('dateformat', 'dd/MM/YYYY'); 

		$params->push($start);
		$params->push($end);

		$params->push(new DropdownField('Range', 'Range', array(
			'' => '-- Range --',
			'Today' => 'Today',
			'Yesterday' => 'Yesterday',
			'Current Week' => 'Current Week',
			'Last Week' => 'Last Week',
			'Current Month' => 'Current Month',
			'Last Month' => 'Last Month',
			'Current Year' => 'Current Year',
			'All Time' => 'All Time',
		)));

        return $params;
	}
    
    public static function getPeriod()
    {
        
        if (!isset($_REQUEST['Range']) || $_REQUEST['Range'] == '') {

            $start = explode('/', $_REQUEST['StartDate']);
            $start = $start[1] . '/' . $start[0] . '/' . $start[2];

            $end = explode('/', $_REQUEST['EndDate']);
            $end = $end[1] . '/' . $end[0] . '/' . $end[2];

            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end));

        } else {

            switch ($_REQUEST['Range']) {
                case 'Today':
                    $startDate = date('Y-m-d', strtotime('today'));
                    $endDate = date('Y-m-d');
                    break;
                case 'Yesterday':
                    $startDate = date('Y-m-d', strtotime('yesterday'));
                    $endDate = date('Y-m-d', strtotime('yesterday'));
                    break;
                case 'Current Week':
                    $startDate = date('Y-m-d', strtotime('last Monday'));
                    $endDate = date('Y-m-d');
                    break;
                case 'Last Week':
                    $week = strtotime('+1 week') - time();
                    $startDate = date('Y-m-d', strtotime('last Monday') - $week);
                    $endDate = date('Y-m-d', strtotime('Sunday') - $week);
                    break;
                case 'Current Month':
                    $startDate = date('Y-m-d', strtotime('first day of this month'));
                    $endDate = date('Y-m-d', strtotime('last day of this month'));
                    break;
                case 'Last Month':
                    $startDate = date('Y-m-d', strtotime('first day of last month'));
                    $endDate = date('Y-m-d', strtotime('last day of last month'));
                    break;
                case 'Current Year':
                    $startDate = date('Y-m-d', strtotime('first day of this year'));
                    $endDate = date('Y-m-d', strtotime('today'));
                    break;
                case 'All Time':
                    $startDate = date('Y-m-d', strtotime('1st January 1970'));
                    $endDate = date('Y-m-d', strtotime('today'));
                    break;
            }

        }
        
        
        return "DATE(Created) >= '$startDate' AND DATE(Created) <= '$endDate'";

    }
    
   

}
