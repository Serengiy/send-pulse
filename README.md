## SendPulse laravel package

### Installation
#### 1.	Add the Service Provider
```
Serengiy\SendPulse\Providers\SendPulseServiceProvider::class
```
#### 2.	Publish the Configuration File
Run the following Artisan command to publish the SendPulse configuration file:
```
php artisan vendor:publish --provider="Serengiy\SendPulse\Providers\SendPulseServiceProvider"
```

#### 3.	Add Environment Variables
Add the following configuration values to your .env file:

```dotenv
PULSE_USER_ID=YOUR_SENDPULSE_USER_ID
PULSE_SECRET=YOUR_SENDPULSE_SECRET
```
