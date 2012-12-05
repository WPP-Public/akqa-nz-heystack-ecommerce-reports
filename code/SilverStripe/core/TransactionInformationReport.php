<?php

use Heystack\Subsystem\Core\ServiceStore;

class TransactionInformationReport extends SS_Report
{
	function title()
	{
		return 'Transaction Information';
	}

    
    function parameterFields()
	{
		$params = new FieldSet();
		
        $params->push(new TextField('TransactionID', 'Transaction ID'));

        return $params;
	}
    
    public static function getTransaction()
    {
        
        $transactionID = $_REQUEST['TransactionID'];
        
        
        // TODO - lastedited for real data
        return DataObject::get_by_id('StoredTransaction', $transactionID);

    }
    
    function getReportField()
	{
        
		if (!isset($_REQUEST['TransactionID']) || $_REQUEST['TransactionID'] == '' || !is_numeric($_REQUEST['TransactionID'])) {

				$content = '<p class="error">Please enter a valid transaction ID.</p>';

		}

		if (!isset($content)) {

            $transaction = self::getTransaction();
            $dos = new DataObjectSet();

            if ($transaction && $transaction->exists()) {

                $productHolder = DataObject::get_one('StoredProductHolder', "ParentID = '" . $transaction->ID . "'");
                $products = DataObject::get('StoredProduct', "ParentID = '" . $productHolder->ID . "'");
                
                $voucherHolder = DataObject::get_one('StoredVoucherHolder', "ParentID = '" . $transaction->ID . "'");
                $vouchers = DataObject::get('StoredVoucher', "ParentID = '" . $transaction->ID . "'");
                
                $shipping = DataObject::get_one('StoredShipping', "ParentID = '" . $transaction->ID . "'");
                
                $fusionPayment = DataObject::get_one('StoredPXFusionPayment', "ParentID = '" . $transaction->ID . "'");
                $postPayment = DataObject::get_by_id('StoredPXPostPayment', $fusionPayment->PXPostPaymentID);

                $render = new ViewableData();	
                
                $transaction->ProductHolder = $productHolder;
                $transaction->Products = $products;
                $transaction->VoucherHolder = $voucherHolder;
                $transaction->Vouchers = $vouchers;
                $transaction->Shipping = $shipping;
                $transaction->FusionPayment = $fusionPayment;
                $transaction->PostPayment = $postPayment;
                
                
                $dos->push($transaction);

                $content = $render->renderWith('TransactionInformationReport',array(
                    'Transactions' => $dos
                ));
                
            } else {
                
                $content = '<p class="error">The transaction for ID ' .$_REQUEST['TransactionID']. ' does not exist.</p>';
                
            }
		
		}

		return new LiteralField('ReportContent', $content);
	}

}