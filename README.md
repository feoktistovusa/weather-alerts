# Weather Alerts Application

## Prerequisites: ##
Create account in MailTrap and OpenWeather.
Update env variables in .env: `OPENWEATHER_API_KEY, MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD`, 
## Setup Instructions

1. **Clone the repository**:

   ```bash
   git clone https://github.com/feoktistovusa/weather-alerts.git
2. **Navigate to the project directory:**
   ```bash
   cd weather-alerts
3. **Install dependencies:**
   ```bash
   composer install
   npm install
4. **Copy .env.example to .env:**
   ```bash
   cp .env.example .env
5. **Generate application key:**
   ```bash
   ./vendor/bin/sail artisan key:generate
6. **Start the application:**
   ```bash
   ./vendor/bin/sail up -d
7. **Run migrations:**
   ```bash
   ./vendor/bin/sail artisan migrate
8. **Running Tests:**
    ```bash
    ./vendor/bin/sail test
9. **Add subscriber to the database:**
    ```bash
   ./vendor/bin/sail artisan tinker
    
   App\Models\Subscriber::create([
    'email' => 'test@example.com',
    'city'  => 'London',
   ]);
 
   App\Models\Subscriber::all();
10. **Manually run the command to check alerts:**
   ```bash
   ./vendor/bin/sail artisan weather:send-alerts -v

If there is any alert for subscriber location the email will be sent. Use any sandbox like Mailtrap to test it locally and specify the env variables in .env: OPENWEATHER_API_KEY, MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD. 
