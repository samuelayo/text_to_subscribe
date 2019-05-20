# How to create a text to subscribe app using Twilio and Laravel

The world has gone global and almost everyone has access to the internet every day. However, we shouldn’t leave out those without smartphones or regular PC’s. One way to cater to them is by creating SMS friendly applications, where even with the worst of phones, they can access our services. In this piece, we will use Laravel and our lovely Twilio to make this idea a breeze to implement.


[View tutorial](#)

## Getting Started
Clone the project repository by running the command below if you use SSH

```
git clone git@github.com:samuelayo/text_to_subscribe.git
```

If you use https, use this instead

```
git clone https://github.com/samuelayo/text_to_subscribe.git
```

Change directory into the newly cloned project and install dependencies

```
cd text_to_subscribe
composer install
```

### Prerequisites

#### Update twillo details

To create a text to subscribe app, we need a phone number where our users can send text messages to, which calls our API to trigger the action. 

Login to your Twilio account if you already have one, or sign up for a new one. Once you are logged in, locate the `#` sign at the left side-bar of the site, and click on it. This would open a new page which shows a button that says “Get your first Twilio phone number”. Click the button and choose the selected number. We also need to grab out twillo credentials. To know where to get your own credentials, please read [here](https://support.twilio.com/hc/en-us/articles/223136027-Auth-Tokens-and-How-to-Change-Them). 

Open `app/Http/Controllers/SubscriptionsController.php` file and do the following:

- Replace the `XXX_ACCOUNT_SID` with your account sid.  
- Replace the `XXX_AUTH_TOKEN` value with the `auth token` you copied out.

#### Migrate database 

Modify the following property in your .env file according to your database settings.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=testProject
DB_USERNAME=root
DB_PASSWORD=
```

Run the Migration

```
php artisan migrate
```


And finally, serve the app:

```
php artisan serve
```
    
