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

-- Shelter Requests table
CREATE TABLE ShelterRequests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    family_members INT NOT NULL,
    current_location VARCHAR(200) NOT NULL,
    urgency_level ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Financial Aid Requests table
CREATE TABLE FinancialAidRequests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_type ENUM('loan', 'insurance') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    purpose TEXT NOT NULL,
    supporting_documents VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Loan Details table
CREATE TABLE LoanRequests (
    loan_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    monthly_income DECIMAL(10,2) NOT NULL,
    employment_status VARCHAR(50) NOT NULL,
    loan_duration INT NOT NULL,
    FOREIGN KEY (request_id) REFERENCES FinancialAidRequests(request_id) ON DELETE CASCADE
);

-- Insurance Details table
CREATE TABLE InsuranceRequests (
    insurance_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    insurance_type VARCHAR(50) NOT NULL,
    policy_number VARCHAR(50) NOT NULL,
    incident_date DATE NOT NULL,
    incident_description TEXT NOT NULL,
    FOREIGN KEY (request_id) REFERENCES FinancialAidRequests(request_id) ON DELETE CASCADE
); 