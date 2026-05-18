-- ============================================================
--  Database Migration - Add Payment Fields to Orders Table
--  Run this if you already have the booknest database set up
-- ============================================================

USE booknest;

-- Add payment tracking columns to existing orders table
ALTER TABLE orders 
ADD COLUMN payment_status ENUM('pending','completed','failed','refunded') DEFAULT 'pending' AFTER status,
ADD COLUMN payment_method VARCHAR(50) DEFAULT NULL AFTER payment_status,
ADD COLUMN payment_id VARCHAR(100) DEFAULT NULL AFTER payment_method,
ADD COLUMN paid_at TIMESTAMP NULL AFTER payment_id;

-- Update existing orders to have completed payment status
UPDATE orders SET payment_status = 'completed', paid_at = placed_at WHERE payment_status = 'pending';

-- Update status enum to include 'pending'
ALTER TABLE orders MODIFY COLUMN status ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending';