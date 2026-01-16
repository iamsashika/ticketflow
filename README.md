# TicketFlow - Event Registration & Ticketing System

A full-stack event management system built with **pure PHP, HTML, CSS, and JavaScript** (no frameworks or external libraries).

## Features

- 🎫 **Event Management** - Create, edit, and manage events
- 👥 **User Registration** - Secure user authentication with role-based access (Admin, Super Admin, User)
- 📝 **Event Registration** - Users can register for events with seat management
- 🖼️ **Event Banners** - Upload event images with vanilla JS preview
- 📊 **Admin Dashboard** - Real-time statistics and insights
- 📋 **Registration Management** - Track all event registrations
- 🏷️ **Category System** - Organize events by categories
- 🔐 **Role-Based Access Control** - Different permissions for admins and users

## Tech Stack

- **Backend**: PHP (Pure/Vanilla)
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Database**: MySQL
- **Server**: Apache (XAMPP)

## Project Structure

```
/Event
├── config/              # Database configuration
├── controllers/         # Controller functions
├── core/               # Core helpers and utilities
├── models/             # Data models (User, Event, Registration, Category)
├── public/             # Public assets (CSS, JS, uploads)
├── views/              # View templates
└── index.php           # Main router
```

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/iamsashika/ticketflow.git
   cd ticketflow
   ```

2. **Set up Apache**

   - Place project in `htdocs` folder (for XAMPP)
   - Or configure virtual host

3. **Create Database**

   - Import `database.sql` (if provided)
   - Or create database manually with the schema

4. **Configure Database**

   - Copy `config/database.php.example` to `config/database.php`
   - Update with your database credentials

5. **Access the Application**
   ```
   http://localhost/Event/
   ```

## Features Overview

### For Users

- Browse upcoming events
- Register for events
- View registration history
- Manage profile

### For Admins

- Create and manage events
- Upload event banners
- View registered attendees
- Manage users
- Create categories
- View statistics and insights

## Database Schema

Key tables:

- `users` - User accounts and authentication
- `events` - Event details and metadata
- `registrations` - Event registrations
- `categories` - Event categories

## Security Features

- Password hashing (bcrypt)
- Input sanitization
- Session-based authentication
- Role-based access control
- SQL injection prevention (PDO prepared statements)
