# Product Catalog

## Project Overview
Laravel-based product catalog system with admin and user functionality.  
Supports products, services, currency rates, and CSV exports.

---

## Prerequisites
- Docker and Docker Compose installed
- Git installed
- PHP 8.1+
- Composer

---

## Getting Started

1. **Clone the repository**

   ```bash
   git clone https://github.com/Jasper8823/E-CommerceInnowise.git
   cd E-CommerceInnowise
Install dependencies

    composer install

Start Docker Sail

    ./vendor/bin/sail up -d
    ./vendor/bin/sail composer install
    
Set up environment variables

Copy the example env file and configure as needed:

    cp .env.example .env
Run migrations and seed database

    ./vendor/bin/sail artisan migrate --seed
Update currency rates

    ./vendor/bin/sail artisan update:currency-rates
Access the app

Open your browser at http://localhost:3333/.

Export Product Catalog to CSV
Overview
This project implements functionality to export products from one Laravel service to another using RabbitMQ.
The products are exported to a CSV file and stored in AWS S3. Upon completion, the catalog administrator is notified via email.

First Service – Export Initiator
Trigger:
Admin clicks the Export to CSV button in the UI.

Behavior:

    Fetch all products from the database.
    
    Divide the products into batches of 100 items.
    
    Dispatch each batch as a job to the default Laravel queue (backed by RabbitMQ).

Technical notes:

    Uses Laravel’s native queue functionality.
    
    Jobs are serialized and sent to RabbitMQ.
    
    No custom RabbitMQ wrapper or exchange customization required.

Second Service – Export Processor
Worker responsibilities:

    A single worker listens for product export jobs.
    
    For each received job (100 products):
    
    Append products to a CSV file named products.csv.
    
    Use Laravel filesystem (S3 driver) to store the file in AWS S3 under a defined path.

Finalization:
    
    After the last batch is processed:
    
    Finalize the CSV file.
    
    Send a notification email to the catalog administrator indicating the export is complete and the file is available in S3.

Acceptance Criteria
    Admin can trigger the export from the UI.
    
    All products are exported in batches of 100 via queued jobs.
    
    A CSV file is created and continuously appended during processing.
    
    The file is stored in AWS S3 upon completion.
    
    An email is sent to the admin with a success message and (optionally) the download link.

Running the Worker
Run the following command to start the queue worker:

    ./vendor/bin/sail artisan queue:work
