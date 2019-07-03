# Overview

This simple web service will react to events sent by github whenever a new repository is being created for an organisation. Upon receieving the [repository event](https://developer.github.com/webhooks/#events) an [Update branch protection](https://developer.github.com/v3/repos/branches/#update-branch-protection) API call will be made to apply a protection to the master branch of this repository and an issue will be created using [Create an issue](https://developer.github.com/v3/issues/#create-an-issue) API call with the details about the protection applied.

This implementation is a proof of concept and is **not intended for production use**. Therefor in order to expose the webservice to the outside world we will use ngrok. 

# Installation

Before you proceed with the installation make sure you have all the required software components installed on your environment. See [Requirements](https://github.com/thorengine/webhooks#requirements) section below for the details.

Clone the repository from github

	git clone https://github.com/thorengine/webhooks.git

Install all the software depoendencies using composer

	php composer.phar install

# Run the web service

Start ngrok on port 8000

	ngrok http 8000
	ngrok by @inconshreveable  (Ctrl+C to quit)
	                                                                                                                                              
	Session Status                online                                
	Session Expires               7 hours, 59 minutes
	Version                       2.3.30                                
	Region                        United States (us)
	Web Interface                 http://127.0.0.1:4040
	Forwarding                    http://7b656e2d.ngrok.io -> http://localhost:8000
	Forwarding                    https://7b656e2d.ngrok.io -> http://localhost:8000
	                                                                                                                                              
	Connections                   ttl     opn     rt1     rt5     p50     p90
	                              0       0       0.00    0.00    0.00    0.00

Once started you will see the external URL that we will need to configure our webhook on github.com (line starting with Forwarding, the URL will look something like this: http://7b656e2d.ngrok.io). Bear in mind that every time you start ngrok this URL will change and the github webhook settings will have to be updated.

Start the web service:

	php -S localhost:8000 -t public public/index.php

Head over to your organisations section on [GitHub](github.com) and open Webhooks section under Settings. CLick the Add webhook button and:
- populate _Payload URL_ with the ngrok URL
- set _Content type_ to _application/json_
- populate _Secret_ with a hard to guess text
- click the _Let me select individual events_ radio button and make sure only _Repositories_ checkbox is checked.
- make sure the _Active_ checkbox at the bottom is also checked
- click _Add webhook_ button to save the changes.

If everything works fine you should see a green checkbox next to your new webhook in the Webhooks section.

# Requirements
- [Composer](https://getcomposer.org/)
- [PHP runtime >=5.6](https://php.net/)
- [ngrok](https://ngrok.com/)

# TODO
- secure the webhook with the secret See [Securing webhooks](https://developer.github.com/webhooks/securing/)
- blank the password from web service settings and replace it with a token provided as an environmental variable
