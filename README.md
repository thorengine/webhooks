# Overview

This simple web service will react to events sent by github whenever a new repository is being created for an organisation. Upon receieving the [repository event](https://developer.github.com/webhooks/#events) an [Update branch protection](https://developer.github.com/v3/repos/branches/#update-branch-protection) API call will be made to apply a protection to the master branch of this repository and an issue will be created using [Create an issue](https://developer.github.com/v3/issues/#create-an-issue) API call with the details about the protection applied.

This implementation is a proof of concept and is **not intended for production use**. Therefor in order to expose the webservice to the outside world we will use ngrok. 

# Installation

Before you proceed with the installation make sure you have all the required software components installed on your environment. See [Requirements](https://github.com/thorengine/webhooks#requirements) section below for the details.

Clone the repository from github

	git clone https://github.com/thorengine/webhooks.git

Install all the software dependencies using composer

	php composer.phar install

# Run the web service

Generate personal access token on github.com in Settings > Developer settings > Personal access tokens (and remember it, cause you will need it later).

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

Head over to your organisations section on [GitHub](github.com) and open Webhooks section under Settings. CLick the Add webhook button and:
- populate _Payload URL_ with the ngrok URL and append _/payload_ to it, ie. http://7b656e2d.ngrok.io/payload
- set _Content type_ to _application/json_
- populate _Secret_ with a hard to guess text
- click the _Let me select individual events_ radio button and make sure only _Repositories_ checkbox is checked.
- make sure the _Active_ checkbox at the bottom is also checked
- click _Add webhook_ button to save the changes.

Start the web service using the secret provided when you set up the webhook and personal token generated:

	GITHUB_SECRET=<secret> GITHUB_TOKEN=<token> php -S localhost:8000 -t public public/index.php

If everything works fine you should see a green checkbox next to your new webhook in the Webhooks section.

# Test the webhook

1. Head over to github.com and open your organisation page
2. Create a new repository (don't forget to tick _Initialize this repository with a README_ checkbox)
3. Go to your repository _Settings > Branches_ and confirm protection has been applied to the master branch
4. Go to your reposity _Issues_ and confirm an issue has been created and contains the protection details 


# Requirements
- [Composer](https://getcomposer.org/)
- [PHP runtime >=5.6](https://php.net/)
- [ngrok](https://ngrok.com/)

