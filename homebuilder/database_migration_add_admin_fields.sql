ALTER TABLE projects
  ADD COLUMN admin_status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  ADD COLUMN admin_message TEXT NULL,
  ADD COLUMN estimated_days INT NULL,
  ADD COLUMN budget_issue VARCHAR(100) NULL,
  ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL;

