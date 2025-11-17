# Confluence-like Application

A full-featured collaboration and documentation platform built with Laravel 11, similar to Confluence.

## Features

- **Spaces & Pages**: Organize content into spaces with hierarchical pages
- **Rich Text Editing**: WYSIWYG editor with TinyMCE
- **Comments**: Threaded comments on pages
- **File Attachments**: Upload and manage file attachments
- **Version History**: Track and restore page versions
- **Search**: Full-text search across all content
- **User Profiles**: User profiles with activity history
- **Activity Feed**: Track all user activities
- **Permissions**: Role-based access control for spaces and pages

## Installation

1. Install dependencies:
```bash
composer install
npm install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Configure database in `.env`

5. Run migrations:
```bash
php artisan migrate
```

6. Start development server:
```bash
php artisan serve
```

## Technology Stack

- Laravel 11
- MySQL/PostgreSQL
- TinyMCE (Rich Text Editor)
- Laravel Scout (Search)
- Tailwind CSS
- Vue.js

## License

MIT

