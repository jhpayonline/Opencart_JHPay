# How to Install this extension on OpenCart 3

### 1. Install the Extension
- Login to OpenCart admin, go to **Extensions > Installer**, and upload the gateway `jhpay.ocmod.zip` file
- Go to **Extensions > Modifications** and click "Refresh".

### 2. Activate the Payment Gateway
- Go to **Extensions > Payments**.
- Find payment gateway **JHPAY**, click "Install", then "Edit" to configure it.
- Enter the required API credentials and set the status to "Enabled".

### 3. Set your Callback URL in merchant's cabinet
- URL: https://site.com/index.php?route=extension/payment/jhpay/callback (replace site.com to your)

### 4. Test the Gateway
- Place a test order to ensure everything works correctly.

That's it!
