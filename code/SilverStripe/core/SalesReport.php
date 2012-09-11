<?php

use Heystack\Subsystem\Core\ServiceStore;

class SalesReport extends SS_Report
{
	function title()
	{
		return 'Sales Report';
	}

    
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
			'Current Week' => 'Current Week',
			'Last Week' => 'Last Week',
			'Current Month' => 'Current Month',
			'Last Month' => 'Last Month'
		)));

        return $params;
	}
    
    public static function getTransactions()
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
            }

        }
        
        // TODO - lastedited for real data
        return DataObject::get('StoredTransaction',"DATE(Created) >= '$startDate' AND DATE(Created) <= '$endDate'", 'Created');

    }
    
    public static function getCurrencies()
    {
        $currencyService = ServiceStore::getService(Heystack\Subsystem\Ecommerce\Services::CURRENCY_SERVICE);
        return new DataObjectSet($currencyService->getCurrencies());
    }
    
    function getReportField()
	{
        
		if (!isset($_REQUEST['Range']) || $_REQUEST['Range'] == '') {

			if ( (!isset($_REQUEST['StartDate']) || $_REQUEST['StartDate'] == '') || (!isset($_REQUEST['EndDate']) || $_REQUEST['EndDate'] == '' )) {
				$content = '<p class="error">Please select a start and end date</p>';
			}

		}

		if (!isset($content)) {

            $transactions = self::getTransactions();
            
            $currencies = self::getCurrencies();
            

            $productCount = 0;
            
            if ($transactions && $transactions->exists()) {
                
                foreach ($transactions as $transaction) {

                    $productHolder = DataObject::get_one('StoredProductHolder', "ParentID = '" . $transaction->ID . "'");
                    $productCount += $productHolder->NoOfItems;

                }
                
                $render = new ViewableData();	

                $content = $render->renderWith('SalesReport',array(
                    'Transactions' => $transactions,
                    'ProductCount' => $productCount,
                    'Currencies' => $currencies
                ));
                
            } else {
                
                $content = '<p class="error">Sorry, no data available for this period.</p>';
                
            }
		
		}

		return new LiteralField('ReportContent', $content);
	}

}

class SalesReport_Controller extends LeftAndMain
{
   
    
    public function getTotalOrderData()
    {
        
        $transactions = SalesReport::getTransactions();

        $data = array();
        
        if ($transactions) {
            foreach ($transactions as $transaction) {

                $data[date("Y-m-d", strtotime($transaction->Created))] += 1;

            }
        }
        
        return json_encode(
                array(array(
                    'label' => 'Total Orders',
                    'data' => 
                        array_map(
                            function($key, $value) { return array(date("U", strtotime($key)) * 1000, $value); },
                            array_keys($data),
                            array_values($data)
                        )        
                ))
        );
        
    }
    
    public function getTotalSpentData()
    {
        
        $transactions = SalesReport::getTransactions();
        
        $currency = $this->URLParams['ID'];

        $data = array();
        
        if ($transactions) {
            foreach ($transactions as $transaction) {
                
                if ($transaction->Currency == $currency) {
                
                    $data[$transaction->Currency][date("Y-m-d", strtotime($transaction->Created))] += $transaction->Total;
                }
                
            }
        }
        
        $totalSpentData = array();
        
        foreach ($data as $key => $info) {
            
            $totalSpentData[] =  array(
                'label' => $key,
                'data' => 
                    array_map(
                        function($key, $value) { 
                            return array(date("U", strtotime($key)) * 1000, $value); 
                        },
                        array_keys($info),
                        array_values($info)
                    )

            );

        }
        
        return json_encode($totalSpentData);

    }
    
}