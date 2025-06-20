# Laravel chat reverb
This repository contains an example of how to create a real-time chat using Laravel + Reverb with Vue.js.

---
## Installation (with Docker + Laravel Sail)
First install the project dependencies:
```bash
composer install
```

After that, configure the application containers:
```bash
sail up -d
```

Install the frontend dependencies:
```bash
sail npm install
```

Copy the `.env.example` file to `.env` and set the environment variables as needed.

Run the database migrations:
```bash
sail artisan migrate
```

**Optional:** If you want to populate the database with some initial data, run:
```bash
sail artisan db:seed --class=ChatExampleSeeder
```

**Optional:** If you want to populate only users, run:
```bash
sail artisan ti --execute "User::factory(20)->create()"
```

Set up the application key:
```bash
sail artisan key:generate
```

Build the frontend assets:
```bash
sail npm run build
```

Run the reverb server:
```bash
sail artisan reverb:start
```

Access the application in the browser at the url [http://localhost/login](http://localhost/login).

### Example Seeder or Tinker user factory
If you have run the "ChatExampleSeeder" seeder, you can use the example user:
- user: user1@example.com
- password: password

Or if you run the Tinker user factory, you get any created user from DB and go to
[chat room](http://localhost/chat/room) and search by any name to start a new chat room.

Access the screen [http://localhost/chat/room/1](http://localhost/chat/room/1) to see the chat in action.
NOTE: You can log in with 'user2@example.com' to better view the chat in real time.

---
## In development
