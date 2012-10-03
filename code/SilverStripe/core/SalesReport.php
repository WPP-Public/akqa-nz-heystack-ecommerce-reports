<?php

use Heystack\Subsystem\Core\ServiceStore;

class SalesReport extends Heystack_SS_Report
{
	function title()
	{
		return 'Sales Report';
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

            $period = self::getPeriod();
            $transactions = DataObject::get('Storedtransaction', $period, 'Created');
            
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
        
        $period = SalesReport::getPeriod();
        
        $transactions = DataObject::get('StoredTransaction', $period, 'Created');
        
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
        
        $period = SalesReport::getPeriod();
        
        $transactions = DataObject::get('StoredTransaction', $period, 'Created');
        
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