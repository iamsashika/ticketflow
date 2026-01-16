-- Add quantity column to registrations table
ALTER TABLE registrations
ADD COLUMN quantity INT NOT NULL DEFAULT 1 AFTER ticket_type_id;
