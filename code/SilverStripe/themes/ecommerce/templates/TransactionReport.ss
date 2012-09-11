<h1>Transaction #$Transaction.ID</h1>

<h2>Transaction</h2>
<table>
	<tr>
		<td><h3>Total</h3></td>
		<td><h3>Tax Total</h3></td>
		<td><h3>Currency</h3></td>
		<td><h3>Status</h3></td>
		
	</tr>
	<tr>	
		<% control Transaction %>
		<td>$Total</td>
		<td>$TaxTotal</td>
		<td>$Currency</td>
		<td>$Status</td>
		<% end_control %>
	</tr>
</table>

<h2>Shipping</h2>
<table>

	<tr>
		<td><h3>Address</h3></td>
		<td><h3>City</h3></td>
		<td><h3>Postcode</h3></td>
		<td><h3>Country</h3></td>
		<td><h3>Title</h3></td>
		<td><h3>FirstName</h3></td>
		<td><h3>Surname</h3></td>
		<td><h3>Email</h3></td>
	
		<td><h3>Shipping Cost</h3></td>
		
	</tr>

	<% control Shipping %>
	<tr>	
		<td>$AddressLine1, $AddressLine2</td>
		<td>$City</td>
		<td>$Postcode</td>
		<td>$Country</td>
		<td>$Title</td>
		<td>$FirstName</td>
		<td>$Surname</td>
		<td>$Email</td>
		<td>$Total</td>
	</tr>
	<% end_control %>
	<tr>
		<td><h3>Gift</h3></td>
		<td colspan="8"><h3>Gift Message</h3></td>
	</tr>
	<% control Shipping %>
	<tr>
		<td><% if IsGift %>Yes<% else %>No<% end_if %></td>
		<td colspan="8">$GiftMessage</td>
	</tr>
	<% end_control %>
</table>

<h2>Billing</h2>
<table>

	<tr>
		<td><h3>Address</h3></td>
		<td><h3>City</h3></td>
		<td><h3>Postcode</h3></td>
		<td><h3>Country</h3></td>
		<td><h3>Title</h3></td>
		<td><h3>FirstName</h3></td>
		<td><h3>Surname</h3></td>
		<td><h3>Email</h3></td>
		
	</tr>
	<% control Shipping %>
	<tr>	
		<td>$BillingAddressLine1, $BillingAddressLine2</td>
		<td>$BillingCity</td>
		<td>$BillingPostcode</td>
		<td>$BillingCountry</td>
		<td>$BillingTitle</td>
		<td>$BillingFirstName</td>
		<td>$BillingSurname</td>
		<td>$BillingEmail</td>
	</tr>
	<% end_control %>
</table>

<h2>Products</h2>
<table>

	<tr>
		<td><h3>Name</h3></td>
		<td><h3>Quantity</h3></td>
		<td><h3>Price</h3></td>
		<td><h3>Color</h3></td>
		
	</tr>
	<% control Products %>
	<tr>	
		<td>$Title</td>
		<td>$Quantity</td>
		<td>$Total</td>
		<td><% if Color %>$Color<% else %>none<% end_if %></td>
	</tr>
	<% end_control %>
</table>

<h2>Vouchers</h2>
<table>

	<tr>
		<td><h3>Code</h3></td>
		<td><h3>Value</h3></td>
		
	</tr>
	<% control Vouchers %>
	<tr>	
		<td>$Code</td>
		<td>$Value</td>
	</tr>
	<% end_control %>
</table>





