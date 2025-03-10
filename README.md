# Walletano-Email-to-Lightning

Easily convert your company email accounts to Lightning Addresses via Walletano.  
This script will let you create lightning addresses for any email address you have like yourname@yourdomain.com. Just follow the instructions below to start accepting LN (Lightning Network) payments via your own (company) email address.  
It follows the protocol described on https://lightningaddress.com/ as well as certain luds from https://github.com/lnurl/luds.

## Pre-requisites

You can use this script on email addresses where you control the associated domain name. It will work wherever you have access to yourdomain.com and can add some files to your webserver. It won't work on email addresses where the domain name is not controlled by you (for example on @gmail.com, @yahoo.com, @hotmail.com, basically on none of the free mail services).

## Installation

Clone the git repo to a folder in your domain document root. This could be "lnaddresses" or any other folder name you choose.
> git clone git@github.com:Walletano/Walletano-Email-to-Lightning.git lnaddresses  
> git clone https://github.com/Walletano/Walletano-Email-to-Lightning.git lnaddresses

**If you use Apache:**  
Rename sample_.htaccess to .htaccess and move it to the document root of the domain that you want to use. If you changed the folder from "lnaddresses" to something else, you need to replace "lnaddresses" in .htaccess with the correct folder name. If you already have a .htaccess file under your domain name document root, just copy the content of sample_.htaccess to your existing .htaccess.

**If you use nginx:**  
Copy the sample_nginx.conf to your nginx.conf in the server block:

	server {
	    listen 80;
	    server_name yourdomain.com;

	    # Add the provided rewrite configuration here
	    location ^~ /.well-known/lnurlp/ {
		rewrite ^/.well-known/lnurlp/(.*)$ /lnaddresses/lnurl.php?lnaddress=$1 last;
	    }

	    # Define other server configuration here
	    # ...
	}

Replace yourdomain.com with your actual domain name.
Also, remember to replace "lnaddresses" with the correct folder name if you cloned to a custom folder.

### Optional (**but highly advised**):
Rename .sample_config.php to config.php and follow the instructions to create a list of allowed lnaddresses (lightning addresses). If you don't do this, the script will allow you to create as many lnaddresses as you want for your domain. **We strongly suggest** to define a list of allowed_lnaddresses on config.php, otherwise spammers might abuse your domain name and if we observe too many requests from your domain, we will rate limit you.

## Testing   
Test your setup by accessing in your browser: https://yourdomain.com/.well-known/lnurlp/yourname. If everything is OK, you should see a json response with a **callback** URL and **metadata**.  
At the first access of any new user, it will take a little longer as we will create your wallet at Walletano.com and the lnurl associated to yourname@yourdomain.com. The following requests will be much faster.  
To use the funds you'll receive to this wallet and your newly created lightning address, we will email you a link to yourname@yourdomain.com that you can use to claim any current and future funds you receive to this wallet. Please make sure to check your email address (also check Junk and Spam folders) and claim the wallet **BEFORE** you start advertising your lightning address otherwise you won't be able to use the funds you receive to your address.

Once you claimed your wallet you can use any lightning address enabled wallet to test sending some funds to your new lightning address.

## Important  
Please make sure you whitelist our domain name (walletano.com) on your mailserver / mail client as **the only way to claim your wallet** is by clicking on the link we send to the same e-mail address as your desired lightning address.  
So, you can only claim lightning addresses where you have access to e-mails received to the equivalent e-mail address. You need to claim the access to wallet only once (after the wallet is created).   
Walletano does not know if your desired lightning address can receive e-mails or not, so it will try once but we also rate limit number of emails we send out to a specific domain name.
