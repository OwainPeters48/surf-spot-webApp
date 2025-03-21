# Surf Spot WebApp

A Laravel-based full-stack web application where users can post, view, and comment on surf spots. Designed with user interaction, admin control, and accessibility in mind.

## Technologies Used

- **Framework:** Laravel (PHP)
- **Frontend:** Blade templates, Bootstrap, JavaScript
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Version Control:** Git
- **Accessibility:** WCAG 2.0 compliance with ARIA roles and keyboard navigation

## Features

- Users can **create surf spot posts** and comment on their own and others’ posts.
- **Favouriting system** allows users to bookmark their favourite surf spots.
- **Notifications** panel for user updates and admin messages.
- **Role-based access control**:
  - **Admins**: Manage users, posts, and comments.
  - **Users**: Can interact with content but have limited access.
- **Basic analytics** for tracking unique post views (Masters level feature).
- Full **CRUD operations** for surf spots and comments with form validation.
- **WCAG 2.0 accessibility**:
  - ARIA roles for screen readers.
  - Keyboard navigation support.

## Installation Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/OwainPeters48/surf-spot-webapp.git
cd surf-spot-webapp
