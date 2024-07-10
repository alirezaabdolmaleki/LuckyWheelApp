
# Lucky Wheel API

This project implements a Lucky Wheel feature for a festival. Users can spin the wheel to win prizes based on their points. The prizes are awarded according to specified probabilities and inventory levels.

## Requirements

- PHP 7.2.* 
- Composer
- MySQL or another supported database

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/alirezaabdolmaleki/LuckyWheelApp.git
    cd LuckyWheelApp
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:**

    Copy the `.env.example` to `.env` and configure your database connection and other settings.

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database and other configurations.

4. **Run database migrations and seeders:**

    ```bash
    php artisan migrate:fresh --seed
    ```

5. **Serve the application:**

    ```bash
    php artisan serve
    ```

    The application will be available at `http://localhost:8000`.

## API Endpoints

### Spin the Lucky Wheel

- **URL:** `/api/v1/lucky-wheel/`
- **Method:** `GET`
- **Middleware:** `login`

#### Response

- **Success (200):**
  ```json
  {
    "title": "X"
  }
  ```

- **Award Out of Stock (409):**
  ```json
  {
    "error": "Award out of stock"
  }
  ```

- **Insufficient Points (422):**
  ```json
  {
    "error": "Insufficient points"
  }
  ```

- **All Prizes Out of Stock (503):**
  ```json
  {
    "error": "No awards available"
  }
  ```

## Testing

This project includes a set of unit and feature tests to ensure functionality.

1. **Set up the testing environment:**

    Ensure your `phpunit.xml` file is correctly configured for your test database.

2. **Run the tests:**

    ```bash
    php artisan test
    ```

## Code Structure

- **Models:**
  - `User`: Represents the user.
  - `Award`: Represents the prizes available in the lucky wheel.
  - `AwardUser`: Pivot table to store the relationship between users and the prizes they win.

- **Migrations:**
  - Create users table with points column.
  - Create awards table with title, coefficient, and inventory columns.
  - Create award_user table with user_id, award_id, and time columns.

- **Seeders:**
  - UsersTableSeeder: Seeds initial users with 100 points each.
  - AwardsTableSeeder: Seeds the initial awards as specified.

- **Controllers:**
  - `LuckyWheelController`: Handles the logic for spinning the wheel and selecting a prize.

## Additional Information

- **Concurrency Handling:** Ensure the application correctly handles concurrent requests and updates to the inventory.
- **Edge Cases:** Consider edge cases such as all prizes being out of stock and users having edge case point values.
- **Middleware:** Custom middleware for authentication is implemented.

## Deployment

- Push your code to your preferred hosting service.
- Ensure environment variables are set correctly on the server.
- Run migrations and seeders on the production database.

## Contribution

Feel free to fork this repository and contribute by submitting pull requests. Any improvements and bug fixes are welcome.

## License

This project is open-source and available under the [MIT License](LICENSE).
