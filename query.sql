-- Users table (base table for all users)
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'volunteer', 'campaigner', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Volunteers table (extends Users for volunteer-specific data)
CREATE TABLE Volunteers (
    volunteer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    skills ENUM('first_aid', 'search_rescue', 'medical', 'counseling', 'logistics', 'driver', 'communication', 'tech_support') NOT NULL,
    experience_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
    availability ENUM('full_time', 'part_time', 'weekends', 'emergency') NOT NULL,
    location VARCHAR(100) NOT NULL,
    status ENUM('active', 'inactive', 'on_duty') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Campaigners table (extends Users for campaigner-specific data)
CREATE TABLE Campaigners (
    campaigner_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    organization_name VARCHAR(100) NOT NULL,
    organization_location VARCHAR(100) NOT NULL,
    campaign_type ENUM('fundraising', 'awareness', 'relief', 'education') NOT NULL,
    organization_description TEXT,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Campaigns table (for tracking campaigns created by campaigners)
-- CREATE TABLE Campaigns (
--     campaign_id INT AUTO_INCREMENT PRIMARY KEY,
--     campaigner_id INT NOT NULL,
--     title VARCHAR(200) NOT NULL,
--     description TEXT NOT NULL,
--     goal_amount DECIMAL(10,2),
--     current_amount DECIMAL(10,2) DEFAULT 0,
--     start_date DATE NOT NULL,
--     end_date DATE,
--     status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (campaigner_id) REFERENCES Campaigners(campaigner_id)
-- );

-- Volunteer Assignments table (for tracking volunteer work)
-- CREATE TABLE VolunteerAssignments (
--     assignment_id INT AUTO_INCREMENT PRIMARY KEY,
--     volunteer_id INT NOT NULL,
--     campaign_id INT,
--     assignment_type VARCHAR(100) NOT NULL,
--     start_date DATE NOT NULL,
--     end_date DATE,
--     status ENUM('assigned', 'completed', 'cancelled') DEFAULT 'assigned',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (volunteer_id) REFERENCES Volunteers(volunteer_id),
--     FOREIGN KEY (campaign_id) REFERENCES Campaigns(campaign_id)
-- );

-- Sample admin user insertion
INSERT INTO Users (full_name, email, password, role) 
VALUES ('Admin User', 'admin@renewhope.com', 'admin123', 'admin');

-- Skill Training Registration table
CREATE TABLE SkillTraining (
    training_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    skill_type ENUM('computer_basics', 'web_development', 'digital_marketing', 'data_entry', 'graphic_design', 'office_tools') NOT NULL,
    experience_level ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    preferred_time ENUM('morning', 'afternoon', 'evening') NOT NULL,
    comments TEXT,
    status ENUM('pending', 'approved', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
); 