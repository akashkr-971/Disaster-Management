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

INSERT INTO Users (full_name, email, password, role) 
VALUES ('Admin User', 'admin@renewhope.com', 'admin123', 'admin');
INSERT INTO Users (full_name, email, password, role) 
VALUES ('User1', 'user1@renewhope.com', 'user123', 'user');
INSERT INTO Users (full_name, email, password, role) 
VALUES ('Volunteer1', 'volunteer1@renewhope.com', 'volunteer123', 'volunteer');
INSERT INTO Users (full_name, email, password, role) 
VALUES ('Campaigne  ', 'campaigner1@renewhope.com', 'campaigner123', 'campaigner');

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

CREATE TABLE rescue_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE RescueRequests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    emergency_type ENUM('flood', 'landslide', 'fire', 'medical', 'other') NOT NULL,
    num_people INT NOT NULL,
    medical_attention ENUM('yes', 'no') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    district VARCHAR(50) NOT NULL,
    location TEXT NOT NULL,
    additional_info TEXT,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_emergency_type (emergency_type),
    INDEX idx_district (district)
);

-- Food and Medicine Requests table
CREATE TABLE FoodMedicineRequests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    request_type SET('food', 'medicine') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    priority_level ENUM('urgent', 'high', 'medium', 'low') NOT NULL,
    delivery_address TEXT NOT NULL,
    special_requirements TEXT,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_priority (priority_level)
);

CREATE TABLE supply_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    camp_name VARCHAR(100) NOT NULL,
    food_items TEXT,
    medicines TEXT,
    funding_request DECIMAL(10,2),
    comments TEXT,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



ALTER TABLE Campaigners ADD COLUMN verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'
ALTER TABLE supply_requests ADD COLUMN status ENUM('pending', 'approved', 'rejected','completed') DEFAULT 'pending';
