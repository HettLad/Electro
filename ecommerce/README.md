# Modern E-commerce Platform

This is a modern e-commerce platform built with PHP and MySQL. It includes features for both customers and administrators.

## Core Requirements

-   **Customer Login/Signup:** Customers can create an account and log in.
-   **Product Search & Category Filter:** Products can be searched and filtered by category.
-   **Cart System:** Customers can add products to their cart, update quantities, and remove items.
-   **Checkout System:** A seamless checkout process with Razorpay integration.
-   **Order Tracking:** Customers can view their order history and track the status of their orders.
-   **Admin Panel:** An admin panel for managing products and orders.
-   **Order Management for Admin:** Admins can view all orders and update their status.

## Database Structure

The database schema is defined in the `database.sql` file. It includes the following tables:

-   `users`
-   `products`
-   `categories`
-   `cart`
-   `orders`
-   `order_items`
-   `payments`

## Setup

1.  **Import the database:** Import the `database.sql` file into your MySQL database.
2.  **Configure the database connection:** Update the database credentials in `config/db.php`.
3.  **Configure Razorpay:** Update your Razorpay Key ID in `order/checkout.php`.
4.  **Run the application:** Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP).

## Bonus Challenges

The following bonus challenges can be implemented to extend the functionality of the platform:

-   **Product review and rating system:** Allow customers to leave reviews and ratings for products.
-   **Coupon and discount system:** Implement a system for applying coupons and discounts to orders.
-   **Admin analytics:** Provide the admin with analytics on top products, sales, and other metrics.
