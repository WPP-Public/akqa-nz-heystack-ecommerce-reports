<div class="flot-graph" data-type='line-time' data-url="/SalesReport_Controller/getTotalOrderData" style='width:100%;height:300px'></div>

<p>Total Transactions: <strong>$Transactions.Count</strong></p>
<p>Total Products Sold: <strong>$ProductCount</strong></p>

<% control Currencies %>
<h2>$CurrencyCode Total Spent</h2>
<div class="flot-graph" data-type='line-time' data-url="/SalesReport_Controller/getTotalSpentData/$CurrencyCode" style='width:100%;height:300px'></div>
<% end_control %>

